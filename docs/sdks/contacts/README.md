# Contacts

## Overview

### Available Operations

* [publicApiV1ContactsArchive](#publicapiv1contactsarchive) - Archive a contact
* [publicApiV1ContactsAssignContactRole](#publicapiv1contactsassigncontactrole) - Assign a contact role
* [publicApiV1ContactsRemoveContactRole](#publicapiv1contactsremovecontactrole) - Remove a contact role
* [publicApiV1ContactsBulkArchive](#publicapiv1contactsbulkarchive) - Archive contacts in bulk
* [publicApiV1ContactsBulkChangeContactRoleStatus](#publicapiv1contactsbulkchangecontactrolestatus) - Change contact role status in bulk
* [publicApiV1ContactsBulkCreate](#publicapiv1contactsbulkcreate) - Create contacts in bulk
* [publicApiV1ContactsBulkDelete](#publicapiv1contactsbulkdelete) - Delete contacts in bulk
* [publicApiV1ContactsChangeContactRoleStatus](#publicapiv1contactschangecontactrolestatus) - Change a contact role status
* [publicApiV1ContactsCreate](#publicapiv1contactscreate) - Create a contact
* [publicApiV1ContactsList](#publicapiv1contactslist) - List contacts
* [publicApiV1ContactsDelete](#publicapiv1contactsdelete) - Delete a contact
* [publicApiV1ContactsShow](#publicapiv1contactsshow) - Retrieve a contact
* [publicApiV1ContactsUpdate](#publicapiv1contactsupdate) - Update a contact
* [publicApiV1ContactsImportTemplate](#publicapiv1contactsimporttemplate) - Download the contact import template
* [publicApiV1ContactsFindByExternalId](#publicapiv1contactsfindbyexternalid) - Find a contact by external ID
* [publicApiV1ContactsFindByTaxId](#publicapiv1contactsfindbytaxid) - Find a contact by tax ID
* [publicApiV1ContactsActivities](#publicapiv1contactsactivities) - List contact activity
* [publicApiV1ContactsOptions](#publicapiv1contactsoptions) - List contact filter options
* [publicApiV1ContactsStats](#publicapiv1contactsstats) - Get contact statistics
* [publicApiV1ContactsImport](#publicapiv1contactsimport) - Import contacts
* [publicApiV1ContactsPreviewImport](#publicapiv1contactspreviewimport) - Preview a contact import
* [publicApiV1ContactsRestore](#publicapiv1contactsrestore) - Restore an archived contact
* [publicApiV1ContactsSearch](#publicapiv1contactssearch) - Search contacts
* [publicApiV1ContactsUpdateBankAccounts](#publicapiv1contactsupdatebankaccounts) - Replace the bank accounts of a contact
* [publicApiV1ContactsUpdateCustomerProfile](#publicapiv1contactsupdatecustomerprofile) - Update a customer profile
* [publicApiV1ContactsUpdateSupplierProfile](#publicapiv1contactsupdatesupplierprofile) - Update a supplier profile
* [publicApiV1ContactsVerifyCensus](#publicapiv1contactsverifycensus) - Verify a contact against the AEAT census

## publicApiV1ContactsArchive

Archive a contact: a reversible removal that keeps it fully readable (`is_archived: true`).
- Always allowed and idempotent: archiving again returns 200 and keeps the first `archived_at`.
- Requires `contacts:delete`; undo with `POST /companies/{company}/contacts/{contact}/restore`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.archive" method="post" path="/companies/{company}/contacts/{contact}/archive" example="success" -->
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



$response = $sdk->contacts->publicApiV1ContactsArchive(
    company: 'Pollich, Schiller and Kovacek',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `contact`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the contact.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1ContactsArchiveResponse](../../Models/Operations/PublicApiV1ContactsArchiveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsAssignContactRole

Assign `customer`, `supplier`, or `lead` to a contact without changing its other roles. Assigning an already-present role is idempotent and returns the canonical contact.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.assign_contact_role" method="post" path="/companies/{company}/contacts/{contact}/roles/{role}" example="success" -->
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

$request = new Operations\PublicApiV1ContactsAssignContactRoleRequest(
    company: 'Dicki, Shanahan and Goodwin',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsAssignContactRole(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1ContactsAssignContactRoleRequest](../../Models/Operations/PublicApiV1ContactsAssignContactRoleRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1ContactsAssignContactRoleResponse](../../Models/Operations/PublicApiV1ContactsAssignContactRoleResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsRemoveContactRole

Remove one role and its directional profile only when it has no historical or active references. A referenced role returns 422 and can be deactivated instead; removing the final role leaves the contact as unassigned while preserving its identity and history.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.remove_contact_role" method="delete" path="/companies/{company}/contacts/{contact}/roles/{role}" example="success" -->
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

$request = new Operations\PublicApiV1ContactsRemoveContactRoleRequest(
    company: 'Lang, Aufderhar and Jerde',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsRemoveContactRole(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                        | Type                                                                                                                             | Required                                                                                                                         | Description                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                       | [Operations\PublicApiV1ContactsRemoveContactRoleRequest](../../Models/Operations/PublicApiV1ContactsRemoveContactRoleRequest.md) | :heavy_check_mark:                                                                                                               | The request object to use for the request.                                                                                       |

### Response

**[?Operations\PublicApiV1ContactsRemoveContactRoleResponse](../../Models/Operations/PublicApiV1ContactsRemoveContactRoleResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsBulkArchive

Archive up to 500 contacts by `ids` with partial success.
- Always allowed and reversible, one by one, with `POST /companies/{company}/contacts/{contact}/restore`.
- Only per-row failure: a UUID the company does not own (`contact_not_found`); the rest still succeed.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="api_key_expired" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'Gottlieb, Corwin and Bosco',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="api_key_revoked" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'Stark, Kunde and Bode',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: '57ed2136-9d27-4522-839d-a4ec13063d14',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="invalid_api_key" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'Moen, Little and Hansen',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="ip_not_allowed" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'Simonis, Rodriguez and Bartell',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="missing_api_key" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'Schiller - Hilpert',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="parameter_invalid_format" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'af98f068-923c-4577-82ea-0b21fc5295ec',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="parameter_invalid_range" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: '207def65-b0d4-4eb6-80d5-21bc239d445a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="parameter_unknown" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'ade1c5d9-85ed-471c-b421-fdb09ac6ee48',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="retired_active_profile_header" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: '82eeedac-62cf-4ef7-86ef-dd4dc5367038',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="success" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'Senger and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_archive" method="post" path="/companies/{company}/contacts/bulk/archive" example="unsupported_api_version" -->
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

$body = new Components\BulkArchiveBusinessContactsV1Request(
    ids: [
        'de1c5d98-5ed7-41c4-a21f-db09ac6ee48e',
        'f98f0689-23c5-4772-8ea0-b21fc5295ec3',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkArchive(
    company: 'b473d299-2ec3-4ab8-aab8-54729a86d61f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkArchiveBusinessContactsV1Request](../../Models/Components/BulkArchiveBusinessContactsV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsBulkArchiveResponse](../../Models/Operations/PublicApiV1ContactsBulkArchiveResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsBulkChangeContactRoleStatus

Set one customer, supplier or fiscal lead role to `active` or `inactive` for up to 500 contact `ids`, with partial success per UUID. The operation never changes the other commercial roles or user RBAC.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="api_key_expired" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'Hilpert LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="api_key_revoked" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'Rowe, Emmerich and Braun',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: '18c69546-3cc1-41d7-91a9-f4cf9c490a36',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="invalid_api_key" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'Lemke and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="ip_not_allowed" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'Beahan Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="missing_api_key" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'Kreiger, Boyle and Price',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="parameter_invalid_format" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'ef7f0680-3f1f-4a97-8823-a2d85ecff126',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="parameter_invalid_range" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: '2c66c505-07cf-40d7-8b25-981c1054574e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="parameter_unknown" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'cf98d71b-58ce-4c31-b75b-70377da0927e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="retired_active_profile_header" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: '3173b56f-b52b-40ff-9d94-c69015efa157',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="success" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: 'Wunsch, Mann and Legros',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_change_contact_role_status" method="post" path="/companies/{company}/contacts/bulk/status" example="unsupported_api_version" -->
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

$body = new Components\BulkChangeContactRoleStatusV1Request(
    ids: [
        'f98d71b5-8cec-4317-b5b7-0377da0927ef',
        'f7f06803-f1fa-4978-823a-2d85ecff1260',
        'c66c5050-7cf0-4d7b-8259-81c1054574e2',
    ],
    role: Components\BulkChangeContactRoleStatusV1RequestRole::Customer,
    status: Components\BulkChangeContactRoleStatusV1RequestStatus::Active,
);

$response = $sdk->contacts->publicApiV1ContactsBulkChangeContactRoleStatus(
    company: '4b5fa83b-8e38-4ff5-99b6-bd7a67fbe34a',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkChangeContactRoleStatusV1Request](../../Models/Components/BulkChangeContactRoleStatusV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsBulkChangeContactRoleStatusResponse](../../Models/Operations/PublicApiV1ContactsBulkChangeContactRoleStatusResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsBulkCreate

Create or upsert up to 500 canonical contacts. Each row accepts the same identity, cumulative roles and directional profiles as `POST /companies/{company}/contacts`. With `dry_run=true`, validate every row without persisting and return its classification.

### Example Usage: dry_run

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="dry_run" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: 'Spinka Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: '28e66123-72fc-4e95-8f40-55ce365f8718',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="missing_api_key" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: 'Bartoletti, Abshire and Collier',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="parameter_invalid_format" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: 'aae16118-7eae-4acf-8c05-b13b2fe8b101',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="parameter_invalid_range" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: '1397f45f-1250-4cbf-9267-604f4276b6f5',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="parameter_unknown" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: '3c67d0e0-028e-4e0b-939e-ee40a5b430a6',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="retired_active_profile_header" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: '7898daa2-2b71-4777-99bc-2f493322410b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="success" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: 'Rosenbaum - Jacobson',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_create" method="post" path="/companies/{company}/contacts/bulk-create" example="unsupported_api_version" -->
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

$body = new Components\BulkCreateBusinessContactsV1Request(
    contacts: [
        new Components\CreateBusinessContactV1Request(
            name: 'Distribuciones Ejemplo SL',
            kind: Components\CreateBusinessContactV1RequestKind::Company,
            taxId: 'B12345674',
            address: new Components\CreateBusinessContactV1RequestAddress(
                line1: 'Calle Mayor',
                countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
            ),
            roles: [
                Components\CreateBusinessContactV1RequestRole::Customer,
                Components\CreateBusinessContactV1RequestRole::Supplier,
            ],
        ),
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkCreate(
    company: '87ec4cc7-46f5-4f75-8cc0-453225a038ea',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkCreateBusinessContactsV1Request](../../Models/Components/BulkCreateBusinessContactsV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsBulkCreateResponse](../../Models/Operations/PublicApiV1ContactsBulkCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ContactsBulkDelete

Delete up to 500 contacts by `ids` with partial success, applying the same soft delete as the single delete.
- Irreversible through the API; for a reversible one use `POST /companies/{company}/contacts/bulk/archive`.
- Only per-row failure is `contact_not_found`, returned with `id`, `error_code` and `error_message`.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="api_key_expired" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'Brekke, Turner and Abernathy',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="api_key_revoked" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'Quitzon - Lind',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="factuarea_version_invalid_format" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: '838af0e6-6587-4236-ab97-b7ddf300a386',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="invalid_api_key" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'Ritchie, Reynolds and Hermiston',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="ip_not_allowed" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'Keeling LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="missing_api_key" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'Larkin, Leuschke and Carter',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="parameter_invalid_format" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: '223b27e3-5a32-4d14-a2d9-56f3667418b9',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="parameter_invalid_range" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: '17a4557d-b710-4f5b-9141-d21b67bfd4ac',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="parameter_unknown" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: '69ed881b-bb54-4b8d-b1e0-a6a09c7dddb1',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="retired_active_profile_header" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'f86a0e60-7e3b-4168-84b8-d48ef91dc81d',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="success" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'Mills and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.bulk_delete" method="post" path="/companies/{company}/contacts/bulk-delete" example="unsupported_api_version" -->
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

$body = new Components\BulkDeleteBusinessContactsV1Request(
    ids: [
        '9ed881bb-b54b-48d1-8e0a-6a09c7dddb1e',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsBulkDelete(
    company: 'f5d77af1-06a7-4743-85c9-725435fd8655',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\BulkDeleteBusinessContactsV1Request](../../Models/Components/BulkDeleteBusinessContactsV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsBulkDeleteResponse](../../Models/Operations/PublicApiV1ContactsBulkDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsChangeContactRoleStatus

Set one commercial role to `active` or `inactive`. The other role and the platform RBAC role of every user remain unchanged.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'Metz - Hansen',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'Lindgren - Cremin',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: '5778a98f-a250-41f0-9a58-a34326bf0f12',
    contact: '78e7cb17-e637-4d38-96b9-fa1783f91615',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'Hoppe, Bruen and O\'Hara',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'Pfannerstill, Bechtelar and Connelly',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'Skiles LLC',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="parameter_invalid_format" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: '2fd0536a-32a7-4932-bdee-18244b46f27e',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="parameter_invalid_range" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: '90b61aea-a390-4b56-812a-6204199bf0d6',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="parameter_unknown" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: '8579d8e6-1a48-4249-85da-02ede2558a6b',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: '53ecbcef-ca28-4c1b-b714-4b749520dfe2',
    contact: '83c53d7a-0879-4554-b53d-1f931882eb90',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="success" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'Herman Inc',
    contact: '<value>',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.change_contact_role_status" method="patch" path="/companies/{company}/contacts/{contact}/roles/{role}/status" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ContactsChangeContactRoleStatusRequest(
    company: 'bc30da67-92ce-4836-b85f-fe7bc0b40157',
    contact: '61a3948d-dee9-4006-bbf3-f72f9a231362',
    role: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\ChangeContactRoleStatusV1Request(
        status: Components\ChangeContactRoleStatusV1RequestStatus::Active,
    ),
);

$response = $sdk->contacts->publicApiV1ContactsChangeContactRoleStatus(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                    | Type                                                                                                                                         | Required                                                                                                                                     | Description                                                                                                                                  |
| -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                                   | [Operations\PublicApiV1ContactsChangeContactRoleStatusRequest](../../Models/Operations/PublicApiV1ContactsChangeContactRoleStatusRequest.md) | :heavy_check_mark:                                                                                                                           | The request object to use for the request.                                                                                                   |

### Response

**[?Operations\PublicApiV1ContactsChangeContactRoleStatusResponse](../../Models/Operations/PublicApiV1ContactsChangeContactRoleStatusResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsCreate

Create one canonical contact with no role or any combination of `customer`, `supplier` and `lead`; the response `id` is a UUID v7.
- A repeated `Idempotency-Key` replays the original response without duplicating the contact.
- An existing `external_id` is updated idempotently within the company.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="api_key_expired" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Gutmann LLC',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="api_key_revoked" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Hayes Group',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: company_with_customer_and_supplier_roles

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="company_with_customer_and_supplier_roles" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Talleres Mediterráneo S.L.',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B66778895',
    commercialName: 'Talleres Med',
    email: 'facturacion@talleresmed.es',
    phone: '961112233',
    externalId: 'ERP-CONTACT-0042',
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Johns, Herman and Bradtke',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="factuarea_version_invalid_format" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'e38f0928-24ee-4964-abdd-2a6bba090f79',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="invalid_api_key" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Schiller, Walter and Goodwin',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="ip_not_allowed" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Bartoletti, Dooley and Williamson',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="missing_api_key" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Kiehn - Lubowitz',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="parameter_invalid_format" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: '6b1384a6-f18d-453e-89c0-6852c71dafb6',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="parameter_invalid_range" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: '28d18e93-3932-47ea-a106-66e2af185c12',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="parameter_unknown" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'd6518883-78bc-4e46-b538-4ac03f04d318',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="retired_active_profile_header" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: '0cec363b-c9bc-4202-8e52-65cf91bdfd2a',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="success" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: 'Littel LLC',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.create" method="post" path="/companies/{company}/contacts" example="unsupported_api_version" -->
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

$body = new Components\CreateBusinessContactV1Request(
    name: 'Distribuciones Ejemplo SL',
    kind: Components\CreateBusinessContactV1RequestKind::Company,
    taxId: 'B12345674',
    address: new Components\CreateBusinessContactV1RequestAddress(
        line1: 'Calle Mayor',
        countryCode: Components\CreateBusinessContactV1RequestCountryCode::Es,
    ),
    roles: [
        Components\CreateBusinessContactV1RequestRole::Customer,
        Components\CreateBusinessContactV1RequestRole::Supplier,
    ],
);

$response = $sdk->contacts->publicApiV1ContactsCreate(
    company: '4938dd4c-ec14-42b5-8bdf-e10c91da978a',
    body: $body,
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [Components\CreateBusinessContactV1Request](../../Models/Components/CreateBusinessContactV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         | {<br/>"name": "Distribuciones Ejemplo SL",<br/>"kind": "company",<br/>"roles": [<br/>"customer",<br/>"supplier"<br/>],<br/>"tax_id": "B12345674",<br/>"address": {<br/>"line_1": "Calle Mayor",<br/>"country_code": "ES"<br/>}<br/>}                                                                                                                                                                                                                                                                                        |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1ContactsCreateResponse](../../Models/Operations/PublicApiV1ContactsCreateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ContactsList

List canonical contacts with cursor pagination. Filter by `roles` (`customer`, `supplier`, `lead`, or the synthetic `unassigned` relationship; CSV or array), `role_match` (`any` or `all`), role status, kind, fiscal identity, external ID, tags, metadata, archive state and creation date. Contacts with cumulative roles appear once and match every role they contain.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.list" method="get" path="/companies/{company}/contacts" example="success" -->
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

$request = new Operations\PublicApiV1ContactsListRequest(
    company: 'Leuschke - Labadie',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsList(
    request: $request
);

if ($response->businessContactList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                              | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                             | [Operations\PublicApiV1ContactsListRequest](../../Models/Operations/PublicApiV1ContactsListRequest.md) | :heavy_check_mark:                                                                                     | The request object to use for the request.                                                             |

### Response

**[?Operations\PublicApiV1ContactsListResponse](../../Models/Operations/PublicApiV1ContactsListResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsDelete

Soft delete a contact: it disappears from every read surface and its detail answers 404 `contact_not_found`.
- Irreversible through the API (`restore` only unarchives); documents that reference it are preserved.
- Releases the tax ID and `external_id`; emits `contact.deleted`. Use `archive` to keep it readable.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.delete" method="delete" path="/companies/{company}/contacts/{contact}" example="success" -->
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



$response = $sdk->contacts->publicApiV1ContactsDelete(
    company: 'Monahan - Hackett',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `contact`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the contact.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsDeleteResponse](../../Models/Operations/PublicApiV1ContactsDeleteResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsShow

Retrieve a canonical contact by `id` (UUID v7), including its common identity, roles, directional profiles and bank accounts. A UUID owned by another company returns the same 404 `contact_not_found` as an unknown UUID.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.show" method="get" path="/companies/{company}/contacts/{contact}" example="success" -->
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



$response = $sdk->contacts->publicApiV1ContactsShow(
    company: 'Swaniawski Inc',
    contact: '<value>',
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
| `contact`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the contact.                                                                                                                                                                                                                                                                                                                                  |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ContactsShowResponse](../../Models/Operations/PublicApiV1ContactsShowResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ContactsUpdate

Update only the common identity and contact fields. Roles and directional profiles have explicit endpoints so changing customer data can never overwrite supplier defaults. A duplicate fiscal identity or external ID returns 409.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'Jakubowski, McLaughlin and Hyatt',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'Armstrong - Kuphal',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: '873d8e02-da5d-4ac0-9dd0-8151dbe3984b',
    contact: '0ef435e8-105d-4079-b344-a6c9a5c5fcbe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'Gutmann Inc',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'Haag LLC',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'Hills, Treutel and Feeney',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="parameter_invalid_format" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: '13d69271-e70e-4c7a-9b4d-8eb7c46c9bab',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="parameter_invalid_range" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: '8b384609-e939-4402-a46f-90c16dfe898e',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="parameter_unknown" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: '6b0f5e36-4740-47e6-a966-4b65be8f0b8f',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'd389b09c-5858-4a38-9938-64f2d02cfdff',
    contact: '07c9774d-17db-4f0d-838b-8bcadc189a32',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="success" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'Renner Group',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update" method="patch" path="/companies/{company}/contacts/{contact}" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ContactsUpdateRequest(
    company: 'd9f0b0fc-4bdc-406f-a6f8-34d1bf77d2ed',
    contact: '0ffe2bdc-bc8f-443c-a087-dbabeb0e8eeb',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactV1Request(
        metadata: [
            'erp_code' => 'IVA-GEN',
            'ledger_account' => '477000',
        ],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdate(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1ContactsUpdateRequest](../../Models/Operations/PublicApiV1ContactsUpdateRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1ContactsUpdateResponse](../../Models/Operations/PublicApiV1ContactsUpdateResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ContactsImportTemplate

Download the template accepted by `POST /companies/{company}/contacts/import`, generated from the same column descriptors as the importer. `?format=csv` (default) is a UTF-8 CSV attachment with only the headers; `?format=xlsx` is a workbook with the Data, Instructions, Examples and Allowed values sheets. It contains no company data.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import_template" method="get" path="/companies/{company}/contacts/import/template" example="template" -->
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



$response = $sdk->contacts->publicApiV1ContactsImportTemplate(
    company: 'Rempel - Kovacek',
    format: Operations\PublicApiV1ContactsImportTemplateFormat::Csv,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->res !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                    | Type                                                                                                                                                                                                                                                                                                                                                                         | Required                                                                                                                                                                                                                                                                                                                                                                     | Description                                                                                                                                                                                                                                                                                                                                                                  | Example                                                                                                                                                                                                                                                                                                                                                                      |
| ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                    | *string*                                                                                                                                                                                                                                                                                                                                                                     | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                    |                                                                                                                                                                                                                                                                                                                                                                              |
| `format`                                                                                                                                                                                                                                                                                                                                                                     | [?Operations\PublicApiV1ContactsImportTemplateFormat](../../Models/Operations/PublicApiV1ContactsImportTemplateFormat.md)                                                                                                                                                                                                                                                    | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Template format. `csv` (default) returns only the headers; `xlsx` returns a workbook with the Data, Instructions, Examples and Allowed values sheets.                                                                                                                                                                                                                        |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ContactsImportTemplateResponse](../../Models/Operations/PublicApiV1ContactsImportTemplateResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ContactsFindByExternalId

Resolve one canonical contact by its integration `external_id` within the authenticated company. Returns 404 `contact_not_found` when the reference is unknown.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="api_key_expired" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'Koch Group',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="api_key_revoked" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'Dickens LLC',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="factuarea_version_invalid_format" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'bddf5539-6bcd-4d87-a62b-54d105f9488b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="invalid_api_key" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'Heller - Johns',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="ip_not_allowed" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'Leffler, Brekke and Simonis',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="missing_api_key" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'Altenwerth, Flatley and Botsford',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="parameter_invalid_format" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: '7bef7b77-c34b-4139-bd79-dfc9c922d230',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="parameter_invalid_range" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'e5326110-d66a-4f67-a77a-513c9a024e16',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="parameter_unknown" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: '3adc0312-5683-4a67-80a8-1d1f233f9aa0',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="retired_active_profile_header" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: '1defdd4b-0d50-482a-a91d-3f0fc7159cd7',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="success" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: 'O\'Conner - Steuber',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_external_id" method="post" path="/companies/{company}/contacts/find-by-external-id" example="unsupported_api_version" -->
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

$body = new Components\FindBusinessContactByExternalIdV1Request(
    externalId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByExternalId(
    company: '381c3394-b698-4016-9e9d-2665384d1134',
    body: $body,
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\FindBusinessContactByExternalIdV1Request](../../Models/Components/FindBusinessContactByExternalIdV1Request.md)                                                                                                                                                                                                                                                   | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ContactsFindByExternalIdResponse](../../Models/Operations/PublicApiV1ContactsFindByExternalIdResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsFindByTaxId

Resolve one canonical contact by Spanish tax identifier within the authenticated company. The result includes all cumulative roles, so integrations do not need separate client and supplier lookups.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="api_key_expired" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: 'Mosciski and Sons',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="api_key_revoked" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: 'Dibbert Inc',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="factuarea_version_invalid_format" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: '3840a0b4-e0ac-4e22-b4e6-cfdb07712384',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="invalid_api_key" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: 'McGlynn - Nitzsche',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="ip_not_allowed" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: 'Hoeger - O\'Conner',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="missing_api_key" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: 'Schoen, Balistreri and Mertz',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="parameter_invalid_format" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: '09c53e2c-e1a2-4195-8398-5eb25d4719c6',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="parameter_invalid_range" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: '0b5e4122-c516-45a9-b4d8-83d5cc41f27b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="parameter_unknown" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: '709fc092-9a92-4559-bf46-a364c2554985',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="retired_active_profile_header" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: '00a0aca6-ea3d-464f-bb84-f8cef80fc0ea',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="success" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: 'Bashirian LLC',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.find_by_tax_id" method="post" path="/companies/{company}/contacts/find-by-tax-id" example="unsupported_api_version" -->
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

$body = new Components\FindBusinessContactByTaxIdV1Request(
    taxId: '<id>',
);

$response = $sdk->contacts->publicApiV1ContactsFindByTaxId(
    company: '47385f14-982c-4b33-b2c2-d7e401a21783',
    body: $body,
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
| `body`                                                                                                                                                                                                                                                                                                                                                                       | [Components\FindBusinessContactByTaxIdV1Request](../../Models/Components/FindBusinessContactByTaxIdV1Request.md)                                                                                                                                                                                                                                                             | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                           | N/A                                                                                                                                                                                                                                                                                                                                                                          |                                                                                                                                                                                                                                                                                                                                                                              |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                           | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                           | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning). | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                   |

### Response

**[?Operations\PublicApiV1ContactsFindByTaxIdResponse](../../Models/Operations/PublicApiV1ContactsFindByTaxIdResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsActivities

List the combined contact timeline with cursor pagination. Filter by `direction` (`sales`, `purchases`, `relationship`) and by document category. Every item exposes public UUIDs and sanitized metadata; internal database identifiers are never returned.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.activities" method="get" path="/companies/{company}/contacts/{contact}/activities" example="timeline" -->
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

$request = new Operations\PublicApiV1ContactsActivitiesRequest(
    company: 'Huel LLC',
    contact: '<value>',
    direction: [
        Operations\PublicApiV1ContactsActivitiesDirection::Purchases,
    ],
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsActivities(
    request: $request
);

if ($response->paginatedList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                          | Type                                                                                                               | Required                                                                                                           | Description                                                                                                        |
| ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                         | [Operations\PublicApiV1ContactsActivitiesRequest](../../Models/Operations/PublicApiV1ContactsActivitiesRequest.md) | :heavy_check_mark:                                                                                                 | The request object to use for the request.                                                                         |

### Response

**[?Operations\PublicApiV1ContactsActivitiesResponse](../../Models/Operations/PublicApiV1ContactsActivitiesResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsOptions

Retrieve selectable values for contact forms and filters; `field` is required (`country`, `country_code`, `province`, `city` or `tag`).
- Narrow with `search` (max 100 characters), `country_code` and `province`; `limit` defaults to 50 (1-250).
- `country` always returns the full ISO catalogue; requires `contacts:read`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.options" method="get" path="/companies/{company}/contacts/options" example="success" -->
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

$request = new Operations\PublicApiV1ContactsOptionsRequest(
    company: 'Stiedemann, Wyman and Durgan',
    field: Operations\Field::Tag,
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsOptions(
    request: $request
);

if ($response->businessContactOptionsResource !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                    | Type                                                                                                         | Required                                                                                                     | Description                                                                                                  |
| ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------------ |
| `$request`                                                                                                   | [Operations\PublicApiV1ContactsOptionsRequest](../../Models/Operations/PublicApiV1ContactsOptionsRequest.md) | :heavy_check_mark:                                                                                           | The request object to use for the request.                                                                   |

### Response

**[?Operations\PublicApiV1ContactsOptionsResponse](../../Models/Operations/PublicApiV1ContactsOptionsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsStats

Return aggregate counts for unique canonical contacts and their cumulative customer, supplier and lead roles. A contact with both customer and supplier roles counts once in `total` and once in `dual_role`; role counters intentionally overlap.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.stats" method="get" path="/companies/{company}/contacts/stats" example="contact_stats" -->
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



$response = $sdk->contacts->publicApiV1ContactsStats(
    company: 'Heaney, Bergstrom and Welch',
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

**[?Operations\PublicApiV1ContactsStatsResponse](../../Models/Operations/PublicApiV1ContactsStatsResponse.md)**

### Errors

| Error Type              | Status Code             | Content Type            |
| ----------------------- | ----------------------- | ----------------------- |
| Errors\Error            | 400, 401, 403, 404, 429 | application/json        |
| Errors\Error            | 500                     | application/json        |
| Errors\APIException     | 4XX, 5XX                | \*/\*                   |

## publicApiV1ContactsImport

Import contacts from CSV with target roles, field mapping, conflict strategy and optional `dry_run`.
- Returns an `import_uuid` with 200 (sync) or 202 (queued); `dry_run` is synchronous and uses no quota.
- Exhausted allowance returns 429 `import_row_quota_exceeded`; an empty file 422 subcode `empty_import`.
- CSV, TXT, XLSX or XLS up to 10 MB.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="api_key_expired" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Torphy and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="api_key_revoked" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Frami and Sons',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: closed_with_unprocessed_rows

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="closed_with_unprocessed_rows" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Kemmer, Kunde and Boehm',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="factuarea_version_invalid_format" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: '86ccb96e-b598-4fe1-bc78-98d40c071726',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="invalid_api_key" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Kshlerin, Wisoky and Hoppe',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="ip_not_allowed" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Wintheiser - Adams',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="missing_api_key" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Zemlak LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: nothing_changed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="nothing_changed" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Towne - Feest',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="parameter_invalid_format" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: '46a4c7cb-619d-4452-806a-5646d4c3b5d4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="parameter_invalid_range" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: '6fd299d7-6228-41f6-b528-8c5727f95543',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="parameter_unknown" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: '036d6710-e39f-4ad7-9f67-3e9ec1f0ea61',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="retired_active_profile_header" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'f3798778-f69c-4cfd-b395-a7a8e5a12ac3',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="success" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'Ebert - Huels',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.import" method="post" path="/companies/{company}/contacts/import" example="unsupported_api_version" -->
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

$body = new Components\ImportBusinessContactsV1Request(
    file: new Components\ImportBusinessContactsV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsImport(
    company: 'c2506750-d6ac-405e-af8e-7b5e754d595f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->twoHundredApplicationJsonObject !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\ImportBusinessContactsV1Request](../../Models/Components/ImportBusinessContactsV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsImportResponse](../../Models/Operations/PublicApiV1ContactsImportResponse.md)**

### Errors

| Error Type                        | Status Code                       | Content Type                      |
| --------------------------------- | --------------------------------- | --------------------------------- |
| Errors\Error                      | 400, 401, 403, 404, 409, 422, 429 | application/json                  |
| Errors\Error                      | 500                               | application/json                  |
| Errors\APIException               | 4XX, 5XX                          | \*/\*                             |

## publicApiV1ContactsPreviewImport

Validate a contact import without writing. Each row is classified as create, update, add-role, merge candidate, conflict or invalid; ambiguous identities are never merged automatically. A file containing only column headers returns 200 with total 0, no rows and source_headers preserved in their original order, so clients can inspect template columns before adding data.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="api_key_expired" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'Schoen - Murphy',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="api_key_revoked" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'King - Gleichner',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="factuarea_version_invalid_format" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: '5a39f2de-2a10-4f55-9b79-0313424f971f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="invalid_api_key" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'Kozey, Hartmann and Klocko',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="ip_not_allowed" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'Larkin - Denesik',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="missing_api_key" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'Steuber LLC',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="parameter_invalid_format" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: '5e0dcbb4-c065-4b56-8f15-f29fb9dab3de',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="parameter_invalid_range" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: '91e7e385-49ba-475d-a8c5-312b4c0bc20b',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="parameter_unknown" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: '20b9daf7-5747-442c-ba28-259af633718f',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="retired_active_profile_header" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'ea5fb738-cfa9-42fd-aa55-aab763585aef',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="success" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: 'Anderson - Quigley',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.preview_import" method="post" path="/companies/{company}/contacts/import/preview" example="unsupported_api_version" -->
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

$body = new Components\PreviewBusinessContactImportV1Request(
    file: new Components\PreviewBusinessContactImportV1RequestFile(
        fileName: 'example.file',
        content: file_get_contents('example.file');,
    ),
    mapping: [
        'name' => 'Name',
        'tax_id' => 'VAT number',
        'external_id' => 'Id',
    ],
);

$response = $sdk->contacts->publicApiV1ContactsPreviewImport(
    company: '2c805d1d-0bb6-4f04-ba29-f2a2f8693be4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\PreviewBusinessContactImportV1Request](../../Models/Components/PreviewBusinessContactImportV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsPreviewImportResponse](../../Models/Operations/PublicApiV1ContactsPreviewImportResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsRestore

Undo the archival of a contact; nothing else changes and roles keep their previous status.
- Idempotent: restoring a contact that is not archived returns 200 and writes nothing.
- Needs `contacts:write`, not `contacts:delete`.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.restore" method="post" path="/companies/{company}/contacts/{contact}/restore" example="success" -->
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



$response = $sdk->contacts->publicApiV1ContactsRestore(
    company: 'Beatty Inc',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                   |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `contact`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Public identifier (UUID v7) of the contact.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            | *?string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                               | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |

### Response

**[?Operations\PublicApiV1ContactsRestoreResponse](../../Models/Operations/PublicApiV1ContactsRestoreResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsSearch

Search contacts by name, fiscal identity, email, phone, mobile or external ID, optionally restricted to customer, supplier or fiscal lead roles and role status. Results are tenant-scoped and cursor-paginated.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.search" method="get" path="/companies/{company}/contacts/search" example="success" -->
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

$request = new Operations\PublicApiV1ContactsSearchRequest(
    company: 'Towne, Zboncak and Howell',
    q: '<value>',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsSearch(
    request: $request
);

if ($response->businessContactList !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                  | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                 | [Operations\PublicApiV1ContactsSearchRequest](../../Models/Operations/PublicApiV1ContactsSearchRequest.md) | :heavy_check_mark:                                                                                         | The request object to use for the request.                                                                 |

### Response

**[?Operations\PublicApiV1ContactsSearchResponse](../../Models/Operations/PublicApiV1ContactsSearchResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsUpdateBankAccounts

Replace the ENTIRE set of bank accounts of a contact; single accounts cannot be added or dropped.
- `bank_accounts` is required: an empty list deletes every account, and omitting it returns 422.
- Set invariants: one default collection and one default payment account, no repeated IBAN.

### Example Usage: api_key_expired

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="api_key_expired" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'Prohaska LLC',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: api_key_revoked

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="api_key_revoked" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'Reichel, Ritchie and Lakin',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="factuarea_version_invalid_format" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'a8a95c8f-2679-49de-9530-7865376476b4',
    contact: 'dc50ec12-2c97-4b87-be37-6e95f2000a77',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: invalid_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="invalid_api_key" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'Steuber, Connelly and Senger',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: ip_not_allowed

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="ip_not_allowed" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'Koch, Hand and O\'Hara',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="missing_api_key" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'Mertz, Rippin and Schimmel',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="parameter_invalid_format" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'f70e2f2c-8e88-48fd-bb96-d487ebe9e810',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="parameter_invalid_range" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: '073e21fb-c335-44ff-88d2-d151e41ff3bb',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="parameter_unknown" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: '078f9bcd-d2de-4bb8-97bb-c75a549c1efc',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="retired_active_profile_header" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: '1ececf75-2748-4bcc-b81f-966675ece0d3',
    contact: 'a492d2e4-b221-4941-ae53-92b1fd041607',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="success" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'Kuhn - Langworth',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_bank_accounts" method="patch" path="/companies/{company}/contacts/{contact}/bank-accounts" example="unsupported_api_version" -->
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

$request = new Operations\PublicApiV1ContactsUpdateBankAccountsRequest(
    company: 'babd2b29-b344-4de4-921d-efa06d289d65',
    contact: 'e02bcd68-060a-48f6-82bb-9eac8c5d9f66',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
    body: new Components\UpdateBusinessContactBankAccountsV1Request(
        bankAccounts: [],
    ),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateBankAccounts(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                          | Type                                                                                                                               | Required                                                                                                                           | Description                                                                                                                        |
| ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                         | [Operations\PublicApiV1ContactsUpdateBankAccountsRequest](../../Models/Operations/PublicApiV1ContactsUpdateBankAccountsRequest.md) | :heavy_check_mark:                                                                                                                 | The request object to use for the request.                                                                                         |

### Response

**[?Operations\PublicApiV1ContactsUpdateBankAccountsResponse](../../Models/Operations/PublicApiV1ContactsUpdateBankAccountsResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsUpdateCustomerProfile

Update sales, collection, tax and DIR3 defaults for the customer role. Supplier defaults are never changed. The contact must already have the customer role.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_customer_profile" method="patch" path="/companies/{company}/contacts/{contact}/customer-profile" example="success" -->
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

$request = new Operations\PublicApiV1ContactsUpdateCustomerProfileRequest(
    company: 'Carter - Hayes',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateCustomerProfile(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1ContactsUpdateCustomerProfileRequest](../../Models/Operations/PublicApiV1ContactsUpdateCustomerProfileRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1ContactsUpdateCustomerProfileResponse](../../Models/Operations/PublicApiV1ContactsUpdateCustomerProfileResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsUpdateSupplierProfile

Update purchase, payment and tax defaults for the supplier role. Customer defaults are never changed. The contact must already have the supplier role.

### Example Usage

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.update_supplier_profile" method="patch" path="/companies/{company}/contacts/{contact}/supplier-profile" example="success" -->
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

$request = new Operations\PublicApiV1ContactsUpdateSupplierProfileRequest(
    company: 'Effertz - Dooley',
    contact: '<value>',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    factuareaVersion: LocalDate::parse('2026-06-01'),
);

$response = $sdk->contacts->publicApiV1ContactsUpdateSupplierProfile(
    request: $request
);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                | Type                                                                                                                                     | Required                                                                                                                                 | Description                                                                                                                              |
| ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
| `$request`                                                                                                                               | [Operations\PublicApiV1ContactsUpdateSupplierProfileRequest](../../Models/Operations/PublicApiV1ContactsUpdateSupplierProfileRequest.md) | :heavy_check_mark:                                                                                                                       | The request object to use for the request.                                                                                               |

### Response

**[?Operations\PublicApiV1ContactsUpdateSupplierProfileResponse](../../Models/Operations/PublicApiV1ContactsUpdateSupplierProfileResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |

## publicApiV1ContactsVerifyCensus

Verify a name and Spanish tax identifier against the AEAT census before issuing a document. The operation is stateless and fail-open: AEAT outages return `status: unavailable` rather than a server error.

### Example Usage: factuarea_version_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="factuarea_version_invalid_format" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: '1edf97f7-6ece-486f-9d5a-6eb8997de5c4',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: missing_api_key

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="missing_api_key" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: 'Dare Group',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_format

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="parameter_invalid_format" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: 'f8324608-2b54-4f52-887e-75092481eb49',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_invalid_range

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="parameter_invalid_range" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: '618f8deb-ccb4-4348-b255-b553a4038330',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: parameter_unknown

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="parameter_unknown" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: 'f3805233-1d1a-42a6-9d64-37a474e69a61',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: retired_active_profile_header

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="retired_active_profile_header" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: '545fa92e-3637-400b-97f7-4131afd55808',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: success

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="success" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: 'Ferry, Lehner and Bartell',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```
### Example Usage: unsupported_api_version

<!-- UsageSnippet language="php" operationID="public-api.v1.contacts.verify_census" method="post" path="/companies/{company}/contacts/census-verification" example="unsupported_api_version" -->
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

$body = new Components\VerifyBusinessContactCensusV1Request(
    taxId: '<id>',
    name: '<value>',
);

$response = $sdk->contacts->publicApiV1ContactsVerifyCensus(
    company: 'b7e2710f-c052-4f21-934c-c49277e8d03e',
    idempotencyKey: '01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b',
    body: $body,
    factuareaVersion: LocalDate::parse('2026-06-01')

);

if ($response->object !== null) {
    // handle response
}
```

### Parameters

| Parameter                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | Type                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    | Required                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             | Example                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `company`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Public identifier (UUID v7) of the company. Get it from `GET /v1/me` (`data.scope[].id`).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `idempotencyKey`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        | *string*                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Client-generated opaque key (up to 255 characters; UUID v7 recommended) that makes retries safe: the first response is cached and replayed for repeats without re-executing the mutation. Reusing a key with a different body returns `422 idempotency_key_reused`; the values of secret fields (`password`, `secret`…) count as part of the body, so retrying with a corrected secret under the same key is a different request, never the replay of the earlier answer. See the [Idempotency guide](/guides/idempotency). **Required on this operation**: repeating it delivers an effect that cannot be taken back (an email sent, a file generated, a third-party call, a charge), so a request without this header is rejected with `422 idempotency_key_required` before any business logic runs. | 01928f10-7c0e-7c4a-9b7d-2f8a6e3c1d4b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    |
| `body`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  | [Components\VerifyBusinessContactCensusV1Request](../../Models/Components/VerifyBusinessContactCensusV1Request.md)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | :heavy_check_mark:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | N/A                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |
| `factuareaVersion`                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      | Pin the API version (`YYYY-MM-DD`, Stripe-style date versioning) for this request; omit to use the key's pinned version, or the latest if none. Unsupported version → `400 unsupported_api_version`; malformed → `400 parameter_invalid_format`. The effective version is echoed in the `Factuarea-Version` response header. See the [Versioning guide](/guides/versioning).                                                                                                                                                                                                                                                                                                                                                                                                                            | 2026-06-01                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |

### Response

**[?Operations\PublicApiV1ContactsVerifyCensusResponse](../../Models/Operations/PublicApiV1ContactsVerifyCensusResponse.md)**

### Errors

| Error Type                   | Status Code                  | Content Type                 |
| ---------------------------- | ---------------------------- | ---------------------------- |
| Errors\Error                 | 400, 401, 403, 404, 422, 429 | application/json             |
| Errors\Error                 | 500                          | application/json             |
| Errors\APIException          | 4XX, 5XX                     | \*/\*                        |