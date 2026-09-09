# Changelog

All notable changes to the Factuarea PHP SDK are documented here. This project
adheres to [Semantic Versioning](https://semver.org/). The SDK pins the
`Factuarea-Version` it was generated against and sends it on every request.

## [Unreleased]

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

- `Factuarea-Version` stays at `2026-06-04`
  (`FactuareaVersionHook::DEFAULT_VERSION`). This release widens the surface; it
  does not move the API version, so no existing call changes behaviour.
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
