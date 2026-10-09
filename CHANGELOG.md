# Changelog

All notable changes to the Factuarea PHP SDK are documented here. This project
adheres to [Semantic Versioning](https://semver.org/). The SDK pins the
`Factuarea-Version` it was generated against and sends it on every request.

## [0.5.0] — 2026-09-29

Regenerated from the public OpenAPI spec that adds the Tasks and Projects module
and separates issuing an invoice from delivering it: **+81 operations, −0
operations** (564 operations over 457 paths, up from 483 over 399). No operation
was removed or renamed, but some generated enumerations change (see
*Changed — breaking*). The default `Factuarea-Version` moves to `2026-10-01`,
the version whose contract the generated models describe.

### Added

**Tasks and projects** — 80 operations under the `projects:*`, `tasks:*`,
`users:read` and `notifications:*` scopes. Nested resources hang off their parent
accessor (`$sdk->tasks->comments`, `$sdk->projects->columns`), and every method
keeps the name derived from its `operationId` (`publicApiV1TasksCommentsCreate()`).
After the first method of a list, the rest are abbreviated to their suffix.

- **`Projects`** (22 operations): `publicApiV1ProjectsList()`, `Show()`,
  `Create()`, `Update()`, `Delete()`, `Archive()`, `Unarchive()` and
  `FindByKey()`; and, under `$sdk->projects`:
  - `columns` (5): `publicApiV1ProjectsColumnsList()`, `Create()`, `Update()`,
    `Delete()` and `Reorder()`.
  - `customFields` (4): `publicApiV1ProjectsCustomFieldsList()`, `Create()`,
    `Update()` and `Delete()`.
  - `tasks` (2): `publicApiV1ProjectsTasksExport()` and `Import()`.
  - `timeInvoices` (2): `publicApiV1ProjectsTimeInvoicesPreview()` and
    `Create()`.
  - `timeSummary` (1): `publicApiV1ProjectsTimeSummaryShow()`.
- **`Tasks`** (45 operations): `publicApiV1TasksSearch()` (`GET /tasks`),
  `Show()`, `Create()`, `Update()`, `Delete()`, `Duplicate()`, `FindByKey()`,
  `Linked()`, `Assign()`, `Unassign()`, `Status()`, `Move()`, `Reposition()`,
  `BulkUpdate()`, `BulkStatus()` and `BulkDelete()`; and, under `$sdk->tasks`:
  - `comments` (4): `publicApiV1TasksCommentsList()`, `Create()`, `Update()` and
    `Delete()`.
  - `attachments` (5): `publicApiV1TasksAttachmentsList()`, `Show()`,
    `Create()`, `Download()` and `Delete()`; `uploadLinks` (1):
    `publicApiV1TasksUploadLinksCreate()`.
  - `labels` (2): `publicApiV1TasksLabelsAssign()` and `Unassign()`.
  - `relations` (3): `publicApiV1TasksRelationsList()`, `Create()` and
    `Delete()`.
  - `entityLinks` (3): `publicApiV1TasksEntityLinksList()`, `Create()` and
    `Delete()`; `externalLinks` (3): `publicApiV1TasksExternalLinksList()`,
    `Create()` and `Delete()`.
  - `timeEntries` (5): `publicApiV1TasksTimeEntriesList()`, `Show()`,
    `Create()`, `Update()` and `Delete()`; `timer` (1):
    `publicApiV1TasksTimerStart()`.
  - `activities` (1): `publicApiV1TasksActivitiesList()`; `customFields` (1):
    `publicApiV1TasksCustomFieldsSet()`.
- **`TaskLabels`** (5 operations): `publicApiV1TaskLabelsList()`, `Show()`,
  `Create()`, `Update()` and `Delete()`.
- **`TaskTimers`** (2 operations): `publicApiV1TaskTimersCurrent()` and `Stop()`.
  They track time against tasks and are unrelated to the working-day clock of
  `TimeEntries` (clock in, clock out…): the time logged against a task is
  `$sdk->tasks->timeEntries`.
- **`Users`** (2 operations): `publicApiV1UsersMe()` and `List()`.
- **`Notifications`** (3 operations): `publicApiV1NotificationsList()`, `Read()`
  and `MarkAllRead()`.
- **`Agenda`** (1 operation): `publicApiV1AgendaList()`.

Also new:

- `Invoices::publicApiV1InvoicesIssue()` (`POST /invoices/{invoice}/issue`)
  issues a draft (definitive number, VeriFactu record when applicable) without
  emailing it. It is irreversible and consumes a series number, so send an
  `Idempotency-Key`.
- **Webhook events**: 24 new types in the `enabled_events` of
  `CreateWebhookEndpointRequest` and `UpdateWebhookEndpointRequest`, in
  `SendTestEventRequest.type` and in the `WebhookEventPayload` / `EventData`
  unions — `task.*` (10: `created`, `updated`, `deleted`, `status_changed`,
  `completed`, `assigned`, `unassigned`, `moved`, `due_soon`, `overdue`),
  `task_comment.*` (`created`, `updated`, `deleted`), `task_time_entry.*`
  (`created`, `updated`, `deleted`, `invoiced`), `project.*` (`created`,
  `updated`, `archived`, `deleted`) and the invoice events `invoice.issued`,
  `invoice.marked_sent` and `invoice.unsent`. `invoice.sent` is now flagged as
  deprecated: it is an alias of `invoice.issued`, emitted at the same instant
  with the same `data.object`. `EventDeletedObject` gains `object`, the type of
  the deleted resource.
- **API key scopes** (`CreateApiKeyV1Request`, `CreateChildApiKeyV1Request`):
  `projects:read|write|delete`, `tasks:read|write|delete`, `users:read` and
  `notifications:read|write`.
- **Automations**: four task actions (`create_task`, `change_task_status`,
  `assign_task`, `add_task_comment`); the catalog's actions expose the `module`
  that governs them (`null` when transversal); step dead-letter reasons
  `task_target_not_found`, `task_project_not_found` and `module_not_accessible`.

### Changed

- **Issuing an invoice is no longer "sending" it.** `Invoice` publishes
  `is_sent`, `issued_at` and `sent_via` (`email`, `manual` or `null`) and a closed
  `status` set — `draft`, `scheduled`, `issued`, `paid`, `partially_paid`,
  `overdue`, `cancelled`, `annulled` and the legacy `sent`. `issued` and these
  three fields are published only to callers on API version `2026-10-01` or
  later; earlier versions — this SDK's default included — keep publishing an
  issued invoice as `status: sent`, and a `scheduled_action` of `issue` as
  `draft`. The status catalog (`InvoiceStatusItem.value`) declares the same set
  and lists `issued` where earlier versions list `sent`. Pass a later
  `factuareaVersion` on the call to opt in.
- The rest of the invoice contract is available in every API version:
  `Invoices::publicApiV1InvoicesList()` filters by delivery with `is_sent` and
  accepts `status=issued` (`sent` stays an alias); `BulkStatus` accepts
  `new_status: issued`; and scheduling accepts `scheduled_action: issue`.
- **Recurring invoices** declare `generation_mode` (`draft`, `issue` or
  `issue_and_send`) on create, create-from-invoice and update, and publish it on
  `RecurringInvoice`. `send_automatically` is derived from it.

### Changed — breaking

Four generated enumerations follow the contract and lose cases, and three
response fields that were plain strings become enumerations, so code that names
those cases or compares those values must change:

- `ScheduleInvoiceRequestScheduledAction::Draft` is gone; use `::Issue`. The API
  still accepts the wire value `draft` as an alias of `issue`, but the enumeration
  only offers `Issue` and `IssueAndSend`.
- `ExportInvoicesExcelV1RequestStatus::Sent` is gone; use `::Issued` (the API
  still accepts `sent` as an alias).
- `CreateApiKeyV1RequestScope` and `CreateChildApiKeyV1RequestScope` no longer have
  `ClientsRead`, `ClientsWrite`, `ClientsDelete`, `SuppliersRead`,
  `SuppliersWrite` and `SuppliersDelete`. Those scopes belonged to the
  `/v1/clients/*` and `/v1/suppliers/*` routes retired in `0.4.0`; request
  `ContactsRead`, `ContactsWrite` or `ContactsDelete` instead.
- The default `Factuarea-Version` moves from `2026-06-04` to `2026-10-01`
  (`FactuareaVersionHook::DEFAULT_VERSION`). `2026-06-04` was never a version the
  API accepts, so requests that relied on the default were rejected with 400
  `unsupported_api_version`; `2026-10-01` is the contract these models describe
  (invoices are published as `issued`, with `issued_at`, `is_sent` and
  `sent_via`). An integration that needs the previous invoice vocabulary can send
  `Factuarea-Version: 2026-06-01` explicitly; the hook never overwrites it.
- `Invoice.status`, `WebhookEndpoint.enabled_events` and
  `WebhookEndpointWithSecret.enabled_events` declare their closed set of values
  (the nine invoice statuses above; the 149 webhook event types) and are
  therefore generated as enumerations instead of strings. Compare with the
  enumeration cases, or with their `->value`, rather than with string literals.

## [0.4.1] — 2026-09-27

Synchronizes the generated SDK with the reviewed purchase scanner contract and
updates product-facing documentation to use Expenses and Invoices. The scanner
surface covers source uploads, extraction and review, duplicate resolution,
conversion to a purchase draft, archive/restore, and inbound email history.
The default `Factuarea-Version` is unchanged.

## [0.4.0] — 2026-09-21

Regenerated from the public OpenAPI spec that publishes contacts as the sole
identity resource: **+27 operations, −28 operations** (469 operations, down from
470). Customers and suppliers are now roles (`customer` / `supplier`) of a single
contact identified by its tax ID; the public [Contact migration guide](https://docs.factuarea.com/guides/contact-migration)
covers request fields, scopes and historical IDs. The default `Factuarea-Version`
is unchanged.

### Added

- **`Contacts`** (27 operations): `publicApiV1ContactsList()`, `Search()`,
  `Show()`, `Create()`, `Update()`, `Delete()` (archive), `Restore()`,
  `Archive()`, `BulkArchive()`, `BulkCreate()`, `BulkDelete()`, `Options()`,
  `Stats()`, `Activities()`, `Import()`, `ImportTemplate()`, `PreviewImport()`,
  `FindByTaxId()`, `FindByExternalId()`, `VerifyCensus()`, `AssignContactRole()`,
  `RemoveContactRole()`, `ChangeContactRoleStatus()`,
  `BulkChangeContactRoleStatus()`, `UpdateCustomerProfile()`,
  `UpdateSupplierProfile()` and `UpdateBankAccounts()`.

### Removed — breaking

The legacy `/v1/clients/*` and `/v1/suppliers/*` routes were retired from the
public API on 2026-09-16, so the `Clients` and `Suppliers` SDKs are gone together
with their models. Every operation has a `Contacts` replacement; where the legacy
resource implied a role, filter the replacement with `roles: ["customer"]` or
`roles: ["supplier"]`:

- `Clients::publicApiV1ClientsList|Search|Show|Create|Update|Delete()` and
  `Suppliers::publicApiV1SuppliersList|Search|Show|Create|Update|Delete()` →
  `Contacts::publicApiV1ContactsList|Search|Show|Create|Update|Delete()`.
- `Clients::publicApiV1ClientsStats()` / `Suppliers::publicApiV1SuppliersStats()` →
  `Contacts::publicApiV1ContactsStats()`.
- `Clients::publicApiV1ClientsActivities()` /
  `Suppliers::publicApiV1SuppliersActivities()` →
  `Contacts::publicApiV1ContactsActivities()`.
- `Clients::publicApiV1ClientsBulkCreate()` → `Contacts::publicApiV1ContactsBulkCreate()`.
- `Clients::publicApiV1ClientsBulkDelete()` / `Suppliers::publicApiV1SuppliersBulkDelete()` →
  `Contacts::publicApiV1ContactsBulkDelete()`.
- `Clients::publicApiV1ClientsImport()` / `ImportTemplate()` →
  `Contacts::publicApiV1ContactsImport()` / `ImportTemplate()`.
- `Clients::publicApiV1ClientsVerifyCensus()` → `Contacts::publicApiV1ContactsVerifyCensus()`.
- `Clients::publicApiV1ClientsFindByTaxId()` / `Suppliers::publicApiV1SuppliersFindByTaxId()` →
  `Contacts::publicApiV1ContactsFindByTaxId()`.
- `Clients::publicApiV1ClientsFindByExternalId()` /
  `Suppliers::publicApiV1SuppliersFindByExternalId()` →
  `Contacts::publicApiV1ContactsFindByExternalId()`.
- `Suppliers::publicApiV1SuppliersBulkStatus()` →
  `Contacts::publicApiV1ContactsBulkChangeContactRoleStatus()`.
- `Suppliers::publicApiV1SuppliersToggleActive()` →
  `Contacts::publicApiV1ContactsChangeContactRoleStatus()`.

Legacy client/supplier IDs are not contact UUIDs: resolve them through the
migration guide instead of assuming equality.

## [0.3.1] — 2026-09-10

Regenerated from the Factuarea public OpenAPI spec after the variant stock
contract change: `UpdateProductVariantRequest.stock` no longer declares
`minimum: 0`. Send the current balance to leave it untouched (it may be negative
when delivered documents ran ahead of the incoming stock); any other value is a
manual set and must still be `>= 0` (422 otherwise). `CreateProductVariantRequest`
keeps `minimum: 0`. No operations added, removed or renamed.

## [0.3.0] — 2026-09-09

Regenerated from the public OpenAPI spec published with Factuarea v1.15.26:
**+57 operations, −0 operations** (470 operations over 386 paths, up from 413
over 345). Nothing removed or renamed.

### Added

- **Automation engine** (`automations` module): `Catalog` (catalog and per-trigger
  evaluable fields), `Rules` (create, list, show, update, delete, activate, pause,
  dry run), `Versions` (sealed rule versions), `Runs` (run history and replay),
  `Steps` (per-step history and replay) and `Usage` (monthly budget) — 18
  operations.
- E-commerce stores (WooCommerce/Shopify connections, orders), product options and
  configurations, price-list resolution and the stock ledger — the remaining 39
  operations published since 0.2.0.

### Fixed

- Regeneration no longer fails on the store listings: the spec declares
  `x-speakeasy-pagination` only there, and the Speakeasy PHP generator emitted a
  `next()` closure calling the request constructor with `starting_after` while the
  model exposes `startingAfter` (PHPStan: *Unknown parameter $starting_after*). A
  new overlay drops the extension so those listings paginate like every other one,
  through the hand-written page iterator. See `docs/REGENERATION.md`.

## [0.2.0] — 2026-08-06

Regenerated from the published OpenAPI spec: **+183 operations, −4 operations**
(413 operations over 345 paths, up from 234 over 192). The SDK had been pinned to
the spec frozen on 2026-06-05 and had not been regenerated since.

While the SDK is in `0.x`, a breaking change ships as a `minor`; `1.0.0` is
reserved for the API's GA. See [`docs/VERSIONING.md`](docs/VERSIONING.md).

### Removed — breaking

Four operations were renamed on the API and are gone from the SDK. Each has a
direct replacement, and three of the four replacements already shipped in
`0.1.0`, so for those the migration is a method rename:

- `Clients::publicApiV1ClientsBulkDeleteLegacy()` (`DELETE /clients/bulk`) →
  `Clients::publicApiV1ClientsBulkDelete()` (`POST /clients/bulk-delete`),
  already available in `0.1.0`. Bulk creation is now its own operation,
  `Clients::publicApiV1ClientsBulkCreate()` (`POST /clients/bulk-create`).
- `Suppliers::publicApiV1SuppliersBulkDeleteLegacy()` (`DELETE /suppliers/bulk`)
  → `Suppliers::publicApiV1SuppliersBulkDelete()`
  (`POST /suppliers/bulk-delete`), already available in `0.1.0`. Bulk
  activation/deactivation is now `Suppliers::publicApiV1SuppliersBulkStatus()`
  (`POST /suppliers/bulk-status`).
- `DeliveryNotes::publicApiV1DeliveryNotesChangeStatus()`
  (`POST /delivery_notes/{delivery_note}/change_status`) → the REST
  sub-resources, all three already available in `0.1.0`:
  `publicApiV1DeliveryNotesMarkDelivered()`, `publicApiV1DeliveryNotesCancel()`
  and `publicApiV1DeliveryNotesSign()`. Call the one matching the target status
  instead of passing the status in the body.
- `PurchaseInvoices::publicApiV1PurchaseInvoicesBySupplier($supplier)`
  (`GET /purchase_invoices/by-supplier/{supplier}`) →
  `PurchaseInvoices::publicApiV1PurchaseInvoicesList()` with `supplierId` on the
  request object, or `supplierIdIn` (`supplier_id[in]`) for several suppliers at
  once.

### Changed — breaking

The published spec now documents two optional headers, `Factuarea-Version` and
`X-Active-Profile`, on every operation. They surface as two optional method
parameters, which changes **216 of the 229 method signatures carried over from
`0.1.0`**:

- **45 methods** crossed the four-parameter threshold and now take a single
  request object instead of positional arguments. For example
  `$sdk->proformas->publicApiV1ProformasAccept($proforma, $body, $idempotencyKey)`
  becomes
  `$sdk->proformas->publicApiV1ProformasAccept(new Operations\PublicApiV1ProformasAcceptRequest(proforma: $proforma, body: $body, idempotencyKey: $idempotencyKey))`.
  Every call site of these methods must be updated.
- **171 methods** keep positional arguments but gain `?LocalDate
  $factuareaVersion` and `?string $xActiveProfile` before the trailing
  `?Options $options`. Calls that pass `$options` by name are unaffected; calls
  that pass it positionally must be updated.
- `Factuarea\Sdk\Chain` is renamed to `Factuarea\Sdk\VerifactuChain`, because the
  spec introduced a second chain resource (`TimeEntriesChain`). The accessor is
  unchanged — `$sdk->verifactu->chain` still works — so only code that type-hints
  the class name is affected.

The `Factuarea-Version` parameter is a per-call override. Leaving it `null` keeps
the existing behaviour: `FactuareaVersionHook` sets the pinned version and never
overwrites a header the caller set.

### Added

27 new resources: `absence-balances`, `absence-calendar`, `absence-policies`,
`absence-requests`, `absence-types`, `companies`, `developers`, `emails`,
`employee-invitations`, `employee-seats`, `employees`, `face_submissions`,
`gestoria`, `holidays`, `integrations`, `monthly_time_record_closes`,
`payment_methods`, `payouts`, `payroll_export_formats`, `presence`,
`stripe_autoinvoicing`, `tax-catalog`, `time_balances`, `time_corrections`,
`time_entries`, `time_tracking_settings`, `work_schedules` — most of them the
time-tracking and HR module, which the SDK did not cover at all.

New operations on 14 existing resources: `invoices`, `account`, `clients`,
`deliveryNotes`, `proformas`, `quotes`, `purchaseInvoices`, `products`,
`suppliers`, `recurringInvoices`, `series`, `taxReports`, `webhookEndpoints`,
`verifactu`.

### Notes

- `Factuarea-Version` moves to `2026-10-01`
  (`FactuareaVersionHook::DEFAULT_VERSION`); see *Changed — breaking*.
- The hand-written layer (`FactuareaClient`, `PageIterator`, `IdempotencyHook`,
  `FactuareaVersionHook`, `WebhookVerifier`) and its tests are unchanged: they
  live outside the Speakeasy-managed file set.

## [0.1.0] — 2026-06-05

Initial pre-GA release.

### Added

- Type-safe PHP 8.2+ SDK (`Factuarea\Sdk`) covering the 234 operations of the
  Factuarea public API v1, generated with Speakeasy from the pinned OpenAPI
  document (`spec/openapi.json`, source commit `e822661bc`).
- Bearer API-key authentication; the key prefix (`fact_test_` / `fact_live_`)
  selects the environment.
- `FactuareaClient::create()` ergonomic factory entry point.
- Pinned `Factuarea-Version: 2026-06-04` header sent on every request
  (`FactuareaVersionHook`), so API behaviour stays stable until the SDK is
  upgraded. See `docs/VERSIONING.md` for the `Factuarea-Version` ↔ SDK-version
  mapping.
- Automatic backoff retries on `429` + `5xx` (honouring `Retry-After`), never on
  other `4xx`.
- Automatic `Idempotency-Key` (UUID v4) on every mutating request, reused across
  retries; overridable per call.
- Cursor auto-pagination via `PageIterator` over the canonical
  `{ data, has_more, next_cursor }` envelope.
- Typed error hierarchy exposing the full error envelope, including `request_id`;
  the API key is never present in any exception message.
- HMAC-SHA256 webhook signature verifier (`WebhookVerifier`) matching the backend
  scheme (`Factuarea-Signature: t=<unix>,v1=<hex>`), with constant-time
  comparison, configurable timestamp tolerance, and rotation grace-window support.

### Notes

- The `Factuarea-Version` baseline for this release is `2026-06-04` (the date of
  the OpenAPI spec frozen in P0, source commit `e822661bc`).
- Pre-GA `0.x`: the public surface may change before `1.0.0`, which is tied to the
  API's GA event. See `SUPPORT.md` and `docs/VERSIONING.md`.

## Unreleased — native CRM source

- Add current ContactPeople, Lead and Pipeline methods/typed DTOs through `Custom\Crm\CrmClient`, generated from a frozen native fragment.
- Preserve omitted/null, UUID identities, decimal strings, original idempotency keys and confirmed receipts; adapt native CRM pagination through the existing page iterator.
- Share the existing HTTP/auth/hooks/errors and avoid automatic retries of non-GET operations. This source update does not publish a package or activate server availability.
