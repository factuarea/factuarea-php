# Changelog

All notable changes to the Factuarea PHP SDK are documented here. This project
adheres to [Semantic Versioning](https://semver.org/). The SDK pins the
`Factuarea-Version` it was generated against and sends it on every request.


## [0.8.0] — 2026-10-17

The SDK moves to the **axis contract** of the public API, on top of the
published `0.7.0` (which still spoke the flat contract of production). The spec
now declares **664 operations** over **534 paths** (up from 612 over 494):
**+57 / −5**. 652 are generated; the 12 seat offer redemption operations are
still not (see *Known gaps*). The default `Factuarea-Version` is unchanged
(`FactuareaVersionHook::DEFAULT_VERSION` stays `2026-10-01`). In `0.x` a breaking
change travels in a `minor`, so this release is `0.8.0`.

What changes for a caller of `0.7.0`:

- **Company resources live under `/v1/companies/{company}/…`** and every company
  method takes the company `id` (UUID v7). This includes everything `0.7.0`
  added on flat URLs: the DeCA transport documents, the Stripe charge rules and
  non-invoiced charges, and the employee batches.
- **Account resources live under `/v1/accounts/{account}/…`**: members and their
  grants, invitations, tax ID transfers, owner transfer, webhooks, request logs,
  provisioning usage and the five API-key operations (which leave the company
  axis), plus the new managed accounts.
- **Contacts replace clients and suppliers**: one tax ID is one contact with
  accumulable `customer` / `supplier` roles, under
  `/v1/companies/{company}/contacts`.
- **`X-Active-Profile` is retired**: the `$xActiveProfile` parameter is gone from
  every signature.
- **Document amounts are decimal strings** and partial updates use `PATCH`.
- **The `oAuth2` security scheme is no longer declared** on the operations
  (the REST API accepts API keys only; the flow authorizes the MCP server):
  `Components\Security::$oAuth2` and the `Hooks\OAuth2Scope` enum are gone.
  `bearerAuth` and `apiKeyAuth` are unchanged.
- The hand-written layer (`src/Custom/`) and its tests are untouched.

The section below is the full breaking window, as measured against `0.6.0`; the
published `0.7.0` entry that follows it is kept as it was released.

### Known gaps

- The seat offer redemption operations (`/employee-seat-offer-redemptions` and
  `/gestoria-seat-offer-redemptions`, 6 each) are still not generated: their
  offer schema uses the JSON Schema `not` keyword, which the generator does not
  support. Call them over HTTP until the contract or an overlay removes it.

### Changed — breaking (since 0.6.0)

- **Every company operation lives under `/v1/companies/{company}/…`**, including
  all the surfaces `0.6.0` added on flat URLs (`tasks`, `projects`, `task-labels`,
  `task-timers`, `purchase-scans`, `purchase-scan-emails`, `notifications`,
  `agenda`, `users`, VeriFactu representation, contact imports, `series`). Each
  method takes the company id; see the detail below.
- **Partial updates use `PATCH`.** The 40 `…Update` operations that were `PUT`
  (contacts, products, invoices, quotes, proformas, delivery notes, purchase
  invoices, recurring invoices, series, tasks, projects, webhook endpoints,
  employees, work schedules, absence types and policies, …) and
  `PurchaseScans::publicApiV1PurchaseScansReview()` are now `PATCH`. Signatures
  do not change.
- `Contacts::publicApiV1ContactsRestore()` is now `POST`.
- Removed with the move to the account axis: the five company API-key operations
  and the six tax write operations (see the detail below).
- Document amounts in webhook and API payloads travel as decimal strings; the
  `2026-10-05` webhook payload version is for new endpoints only.

### Detail of the axis and account-model window

### Changed — breaking

- **Every company operation gains a required `$company` argument.** The company
  stops being inferred in silence from the credential and travels in the URL,
  where it is visible, logged and auditable. Methods that stayed positional take
  it right after the body; methods past the four-parameter threshold take it on
  their request object.

  ```php
  // Before
  $sdk->invoices->publicApiV1InvoicesList();

  // Now
  $sdk->invoices->publicApiV1InvoicesList(company: $companyId);
  ```

  The value is the company **`id`** — the one `publicApiV1AccountShow()` returns
  in `data.scope[].id` — never its tax ID or its name.

- **The identity operation moves from `/v1/account` to `/v1/me`.**
  `$sdk->account->publicApiV1AccountShow()` keeps its name and now answers on
  `/v1/me`, returning the credential's scope (`data.scope[]`) alongside its API
  key.

- **`X-Active-Profile` is retired.** The contract no longer declares it, so the
  `$xActiveProfile` parameter is gone from every signature. The axis segment
  replaces it: what used to be an ambient header is now part of the resource.

- **Amounts travel as decimal strings** and updates use `PATCH`, in step with the
  same single breaking window: see
  https://docs.factuarea.com/docs/changelog/axis-and-payload-breaking-window

### Removed — breaking

- The five API-key operations move from the company to the **account** axis:
  `$sdk->companies->apiKeys->publicApiV1CompaniesApiKeys{List,Create,Show,Revoke,RotateSecret}()`
  → `$sdk->account->apiKeys->publicApiV1AccountApiKeys{List,Create,Show,Revoke,RotateSecret}()`
  on `/v1/accounts/{account}/api-keys`. A key reaches a portfolio, not one
  company.

### Added

- **The account axis (15 operations)**: members and their module access,
  invitations, claim tokens, owner transfer and usage, under
  `/v1/accounts/{account}/…`.
- `Companies::publicApiV1CompaniesIssuingReadiness()` and
  `RecurringInvoices::publicApiV1RecurringInvoicesBulkStatus()`.
- **Parameter descriptions**: every path, query and header parameter now carries
  its description in the method docblocks and in `docs/`. Text only: no
  signature or type changes.
- **UUID v7 ids for product options and configurations**: the `id` of every
  option group, option value and configuration in `CreateProductRequest*` and
  `UpdateProductRequest*` is documented as a UUID v7; the API rejects a new one
  with another UUID version with 422. Text only: no signature or type changes.

### Account model

Regenerated a second time, from the **account-model** contract, inside the same
unpublished `0.5.0` (in `0.x` a breaking change travels in a `minor`, and this
work publishes nothing): **508 operations** in **418 paths**, **+36 / −9**
against the 481 above. Generated with Speakeasy 1.800.1; nothing generated was
edited by hand and `src/Custom/` is untouched. The default `Factuarea-Version`
is unchanged. Same single breaking window as above: see
https://docs.factuarea.com/docs/changelog/axis-and-payload-breaking-window
(the account-model news page is at
https://docs.factuarea.com/docs/changelog/account-model).

#### Removed and replaced — breaking

The claim token of an account becomes a **tax ID transfer**. Each retired method
is listed with the one that replaces it:

| Retired | Replaced by |
|---|---|
| `$sdk->account->claimTokens->publicApiV1AccountClaimTokensAccept()` | `$sdk->account->nifTransfers->publicApiV1AccountNifTransfersAccept()` |
| `$sdk->account->claimTokens->publicApiV1AccountClaimTokensCreate()` | `$sdk->account->nifTransfers->publicApiV1AccountNifTransfersCreate()` |
| `$sdk->account->claimTokens->publicApiV1AccountClaimTokensList()` | `$sdk->account->nifTransfers->publicApiV1AccountNifTransfersList()` |

`NifTransfers` also gains `publicApiV1AccountNifTransfersRevoke()`. The NIF
portfolio stays in `Companies`.

#### Removed without a replacement — breaking

- The six tax write operations are gone from the contract:
  `Taxes::publicApiV1Taxes{Create,Delete,SetDefault,SetDefaultForDocument,Toggle,Update}()`.
- The `oAuth2` security scheme is no longer declared: `Security::$oAuth2` and the
  generated `Hooks\OAuth2Scope` enum are gone. `bearerAuth` and `apiKeyAuth` are
  unchanged.
- `Components\MembershipRole` and `Components\PlatformRole` are gone with no
  replacement: the contract no longer carries `platform_role` nor
  `roles[].membership_role`. `Components\AccountMember` loses `$platformRole`
  and its `$roles` array, and `Components\AccountMemberRole` stops being the
  `{company_id, membership_role}` class and becomes the account-role enum
  (`owner`, `admin`, `member`) that `AccountMember::$role` now uses.

#### Contacts: `create` now declares two success responses — breaking

`Operations\PublicApiV1ContactsCreateResponse` loses `$object` and gains
`$twoHundredApplicationJsonObject` (200, an existing `external_id` was updated
and is returned) and `$twoHundredAndOneApplicationJsonObject` (201, contact
created). Both bodies are `{ data: BusinessContact }`. The contract declares
the two statuses separately for `POST /v1/companies/{company}/contacts`, so read
whichever the call returned; the former `$object` has no equivalent.
`Operations\PublicApiV1ContactsCreateResponseBody` is replaced by
`…ResponseBody1` (200) and `…ResponseBody2` (201).

#### Generated classes renamed — breaking

Speakeasy names the nested types of a response by position and prefixes them
when two operations would share a short name. These classes are removed and
each has a different name now; a type hint, `instanceof` or `use` of the old
name stops resolving. Properties and wire format do not change.

| Before | After |
|---|---|
| `Deliveries` | `WebhookEndpointsDeliveries` |
| `RequestLogs` | `DevelopersRequestLogs` |
| `Components\AccountEnvironment` | `Components\AccountEnvironment1` (`MeAccount::$environment`) and `Components\AccountEnvironment2` (`AccountApiKey::$environment`) |
| `Components\MeEnvironment` | `Components\MeApiKeyEnvironment` |
| `Components\Options` | `Components\CreateInvoiceRequestOptions` |
| `Components\Origin` | `Components\EventDataPaymentReversedOrigin` (`Reversal::$origin`) |
| `Components\Totals` | `Components\RecurringInvoicePreviewDocumentTotals` |
| `Components\TaxDefaultForDocuments` | `Components\DefaultForDocuments` |
| `Operations\ApiRequestLogList` | `Operations\PublicApiV1DevelopersRequestLogsListApiRequestLogList` (and `…AccountRequestLogsList…` for the account axis) |
| `Operations\Method` | `Operations\PublicApiV1DevelopersRequestLogsListMethod` (and `…AccountRequestLogsListMethod`) |
| `Operations\StatusRange` | `Operations\PublicApiV1DevelopersRequestLogsListStatusRange` (and `…AccountRequestLogsListStatusRange`) |

#### Added

- **Managed accounts** (`$sdk->managedAccounts`): `create`, `list`, `show`, the
  `plan` (`preview`, `update`), the `accessLevel` update, `management->end` and
  the reissue of the claim token of a managed account
  (`claimTokens->publicApiV1ManagedAccountsClaimTokensCreate()`, a different
  operation from the retired transfer ones).
- **Bulk import of managed accounts** (`$sdk->managedAccounts->imports`):
  `publicApiV1ManagedAccountsImportsCreate()`, `…Preview()` and `…Template()`.
  The first two send `multipart/form-data` with `accounts_file` (required) and
  `customers_file` (optional) as `Components\AccountsFile` / `CustomersFile`, plus
  an optional `options` object.
- **Account webhooks** (`$sdk->account->webhooks`): `create`, `list`, `show`,
  `update`, `delete`, `rotateSecret`, `test`, and `->deliveries` (`list`, `show`,
  `retry`). They are signed like company webhooks, so the hand-written
  `WebhookVerifier` verifies the account envelope unchanged.
- **Account request logs** (`$sdk->account->requestLogs`): `list` and `show`.
- Also on the account axis: `publicApiV1AccountProvisioningUsage()`,
  `ApiKeys::publicApiV1AccountApiKeysUpdateNifScope()`,
  `Invitations::publicApiV1AccountInvitationsShow()`,
  `Members::publicApiV1AccountMembersShow()` and the member grants
  (`Members->grants`: `list`, `show`, `update`, `delete`), plus
  `Companies::publicApiV1CompaniesStats()`.

## [0.7.0] — 2026-10-14

Regenerated from the public OpenAPI spec that adds the transport control
document (DeCA), Stripe charge rules, employee batches and seat offer
redemptions: **+41 operations, −0 operations** in the spec (612 operations over
494 paths, up from 571 over 461). No operation was removed. 29 of the new
operations are generated; the 12 seat offer redemption operations are not (see
*Known gaps*). Some generated classes gain required fields or are renamed (see
*Changed — breaking*). `Factuarea-Version` stays at `2026-10-01`
(`FactuareaVersionHook::DEFAULT_VERSION` is unchanged).

### Added

**Operations** (29 generated):

- `$sdk->transportDocuments` (new `TransportDocuments` accessor, 17 operations)
  for the electronic control document of Spanish public road freight (DeCA):
  `publicApiV1TransportDocumentsIndex()` (cursor list with `status`,
  `transport_date[gte|lte]`, `company_role`, `carrier_tax_id`,
  `delivery_note_id`, `q` and `sort`), `Statuses()`, `Show()`, `Create()`
  (`CreateTransportDocumentRequest`; only `company_role` is required, and
  `delivery_note_ids` pre-fills one shipment per delivery note), `Update()` and
  `Delete()` (drafts only), `Issue()`, `Modify()`
  (`ModifyTransportDocumentRequest`, with `reason` and `data_origin`), `Close()`,
  `Reopen()`, `Cancel()`, `Supersede()`, `Send()`, `Evidence()`, and the
  `Pdf()`, `Qr()` and `IssuanceProof()` downloads. Issuing, modifying,
  cancelling, superseding and sending are irreversible: send an
  `Idempotency-Key`. Scopes `transport_documents:read|write|transition|delete`,
  and `pdfs:read` for the PDF.
- `$sdk->stripeAutoinvoicing->accounts->chargeRules` (new `ChargeRules`
  accessor, 5 operations): `publicApiV1StripeAutoinvoicingAccountsChargeRulesList()`,
  `Create()`, `Update()`, `Delete()` and `Reorder()` manage the charge treatment
  rules of a connected account: the first matching rule decides how a charge is
  treated, for example excluding it from invoicing.
- `$sdk->stripeAutoinvoicing->accounts->nonInvoicedCharges->publicApiV1StripeAutoinvoicingAccountsNonInvoicedChargesList()`
  lists the charges a treatment rule excluded from invoicing, with
  `refunded_amount`, `refunded_at` and `net_amount`.
- `$sdk->employeeBatches` (new `EmployeeBatches` accessor, 6 operations):
  `publicApiV1EmployeeBatchesPrepare()`, `List()`, `Show()`, `RefreshQuote()`,
  `Confirm()` and `Cancel()` provision 1 to 100 employees, new or inactive, in
  one quoted purchase of their seats. Preparing and refreshing never charge;
  confirm the reviewed `quote_version` with a stable `Idempotency-Key`.

**Models and fields**:

- **DeCA**: `TransportDocument` with its parties, shipments, goods, versions
  (`TransportDocumentVersion`, with the `sha256` of each PDF) and changes, the
  issuance evidence (`TransportDocumentEvidence`) and the request of each step.
  Series accept the `transport_document` document type, and
  `PublicApiV1SeriesStatsData` reports `transport_document`. The ten
  `transport_document.*` events have their payloads and are test event types
  (`SendTestEventRequestType`).
- **Gross transport weight**: products, variants and presentations accept and
  publish `gross_weight_kg`; delivery note lines publish their frozen weight and
  the delivery note `total_gross_weight_kg` and `lines_without_weight_count`.
- **Contact imports**: `BusinessContactImport` gains `updated_count`,
  `warnings_count`, `skip_reasons`, `first_diagnostics` and
  `outcomes_truncated`, and `BusinessContactImportPreview` gains `skip`,
  `updated_count`, `warnings_count`, `skip_reasons` and `outcome_kind`.
- **Taxes**: `Tax.ownership` tells the company's own taxes from the read-only
  Factuarea catalog; the tax list filters by `ownership`.
- **Invoices and documents**: `Invoice` publishes `origin`; quotes and pro-formas
  publish `operation_regime`; stores and Stripe accounts accept an
  `invoice_note`; the quarterly dossier accepts `include_non_invoiced_charges`.

### Changed — breaking

No operation disappears, but some generated classes follow the contract:

- New required constructor arguments, so build these classes with named
  arguments: `AutomationUsage` (`activeRules`), `BusinessContactImport`,
  `BusinessContactImportError` (`row`), `BusinessContactImportPreview`
  (`skip`, `skipReasons`), `ConnectedAccount` (`connectionMethod`,
  `authorizationStatus`), `Invoice` and `InvoiceWithCheckoutBlocks` (`origin`),
  `Proforma` and `Quote` (`operationRegime`), `ProjectTasksImport`
  (`warningsTruncated`, `warningsTotal`), `Tax` (`ownership`), `UsedBy`
  (`supplierProfiles`, `recurringInvoices`, `companyTaxDefaults`) and
  `PublicApiV1SeriesStatsData` (`transportDocument`).
- `BusinessContactImport::$startedAt` and `$finishedAt` are `?\DateTime`
  instead of `?string`, and `$object` and `$entityType` are enumerations
  (`BusinessContactImportObject`, `BusinessContactImportEntityType`).
  `BusinessContactImportError::$code` and `$message` are no longer nullable.
- Renamed classes: `Components\Origin` is now
  `Components\EventDataPaymentReversedOrigin` (`Reversal::$origin` changes type
  accordingly) and `Components\Outcome` is
  `Components\BusinessContactImportOutcome` (`BusinessContactImport::$outcomes`).
- `Components\Security` loses the duplicated `http` scheme, which the published
  spec no longer declares. Authenticate with `bearerAuth`, `apiKeyAuth` or
  `oAuth2` as before.
- `Event::$data` and `WebhookDelivery::$payload` accept the new
  `transport_document.*` payloads.

### Known gaps

- The seat offer redemption operations (`/employee-seat-offer-redemptions` and
  `/gestoria-seat-offer-redemptions`, 6 each) are not generated: their offer
  schema uses the JSON Schema `not` keyword, which the generator does not
  support. Call them over HTTP until the contract or an overlay removes it.

### Notes

- The hand-written layer (`FactuareaClient`, `PageIterator`, `IdempotencyHook`,
  `FactuareaVersionHook`, `WebhookVerifier`) and its tests are unchanged.

## [0.6.0] — 2026-10-05

Regenerated from the public OpenAPI spec that adds unattended checkout on
invoice creation, the VeriFactu representation of the company and contact import
follow-up: **+7 operations, −0 operations** (571 operations over 461 paths, up
from 564 over 457). No operation was removed. Some generated classes are renamed
or change shape (see *Changed — breaking*). `Factuarea-Version` stays at
`2026-10-01` (`FactuareaVersionHook::DEFAULT_VERSION` is unchanged).

### Added

**Operations** (7):

- `$sdk->contacts->imports` (new `Imports` accessor, 2 operations):
  `publicApiV1ContactsImportsShow()` (`GET /contacts/imports/{id}`) retrieves a
  contact import with its counters, per-row outcomes and status, and
  `publicApiV1ContactsImportsErrors()` downloads its error report. Importing
  contacts now returns a stable import UUID for synchronous and queued results;
  the 429 `import_row_quota_exceeded` rejection applies when the monthly row
  allowance is exhausted.
- `$sdk->verifactu->representation` (new `Representation` accessor, 3
  operations): `publicApiV1VerifactuRepresentationShow()` retrieves the active
  representation, `publicApiV1VerifactuRepresentationRegister()` registers one
  (`RegisterCompanyRepresentationV1Request`: `kind`, `signer_name`,
  `signer_tax_id`, `granted_on`, the evidence `document` or the power-of-attorney
  reference, and an optional `valid_until`) and
  `publicApiV1VerifactuRepresentationRevoke()` revokes it. Registering and
  revoking are irreversible, so send an `Idempotency-Key`.
- `$sdk->verifactu->records->publicApiV1VerifactuRecordsRetryBlocked()`
  (`POST /verifactu/records/retry-blocked`) retries every blocked VeriFactu
  record in one call. Send an `Idempotency-Key`.
- `$sdk->series->publicApiV1SeriesUpdate()` (`PUT /series/{series}`) updates a
  series (`UpdateSeriesRequest`: `name`, `code`, `counter_reset`, `year_reset`,
  `number_format`, `initial_number`, `invoice_kind`, `document_type`). Send an
  `Idempotency-Key`.

**Models and fields**:

- **Unattended checkout on `invoices.create`**: `CreateInvoiceRequest` gains
  `payment` (`Payment`: `method`, `paid_at`, `reference`; the payment is
  registered for the whole amount due and the invoice ends up paid), `type`
  (`F1` or `F2`, `CreateInvoiceRequestType`), `prices_include_tax` and
  `operation_on`; `client_id` is optional on a simplified invoice (`F2`).
  `Options` gains `register_verifactu`. `CreateInvoiceRequestMethod` enumerates
  the payment methods (`bank_transfer`, `direct_debit`, `sepa_direct_debit`,
  `cash`, `credit_card`, `check`, `paypal`, `bizum`, `other`). When the request is
  a checkout, the response (`InvoiceWithCheckoutBlocks`, an `Invoice` plus
  three blocks) carries `verifactu` (`Verifactu`: `status`, `error_code`,
  `aeat_status`, `huella`, `qr_url`, `qr_png_base64`, `legend`, `csv`), `pdf`
  (`Pdf`: `status`, `url`, `expires_at`) and `public_url`.
  `UpdateInvoiceRequest` gains `type` and `operation_on`, and `Invoice`
  publishes `operation_on`.
- **Invoice PDF format**: `publicApiV1InvoicesPdf()` and
  `publicApiV1InvoicesPdfLink()` accept `format` (`a4`, `ticket_80`,
  `ticket_58`).
- **Series by invoice kind**: `Series.invoice_kind` and `CreateSeriesRequest`,
  `UpdateSeriesRequest` `invoice_kind` (`complete`, `simplified`, `corrective`,
  `simplified_corrective`); `publicApiV1SeriesList()` filters with `invoice_kind`
  and `invoice_kind_in`, and `publicApiV1SeriesActive()` and
  `publicApiV1SeriesDefault()` accept `invoice_kind`. `publicApiV1SeriesActive()`
  also accepts `document_type`.
- **Corrective invoices**: `CreateCorrectiveInvoiceRequest` gains
  `correction_nature` (`CorrectionNature`: `I` or `S`) and `series_id`, and its
  lines gain `unit`, `regime_key` and `exemption_reason` (enumerations) plus
  `exemption_reason_text`.
- **Annulment**: `AnnulInvoiceV1Request.revert_collections` and, on
  `CanAnnulInvoice`, `requires_collection_reversal` and
  `active_collections_amount`. The payment reversal reason gains `issued_in_error`.
- **VeriFactu records**: `VeriFactuRecord` gains `is_blocked`, `can_subsanar`,
  `aeat_error_code`, `block_reason` (`MISSING_CERTIFICATE`,
  `MISSING_REPRESENTATION`, `SYSTEM_CERTIFICATE_UNAVAILABLE`,
  `PRESENTER_NOT_ENABLED`, `SUBMISSION_REJECTED`) and
  `aeat_warning_requires_subsanation`; `VeriFactuStats` gains
  `pending_incident_count`, `blocked_incident_count` and `oldest_pending_at`;
  the subsanation result documents the UUID of the new record it creates. The
  `ErrorCode` enumeration lists the remission error codes
  (`certificate_missing`, `certificate_expired`, `certificate_revoked`,
  `certificate_nif_mismatch`, `clock_drift_exceeded`, `representation_required`,
  `system_certificate_unavailable`, `verifactu_not_enabled`).
- **VeriFactu remission mode**: `VeriFactuConfig` gains `remission_mode`
  (`own_certificate`, `social_collaborator`, `power_of_attorney`),
  `has_active_representation`, `active_representation_id`,
  `active_representation_kind`, `active_representation_valid_until`,
  `active_representation_is_expired`, `social_collaborator_available` and
  `presenter_certificate_status` (`valid`, `invalid`, `not_configured`);
  `UpdateVeriFactuSettingsV1Request` accepts `remission_mode`.
- **Invoice activity**: `InvoiceActivity.metadata` is now the typed `Metadata`
  model (`record_type`, `requires_subsanation`, `attempt`, `reason`,
  `block_reason`, `recipient`, `views_today`, `amount`, `method`,
  `changed_fields`, `downloaded_pdf`, `csv`, `aeat_error_code`,
  `aeat_error_message`, `next_retry_at`, `provider`). The timeline now includes
  the result of every VeriFactu submission, undelivered emails, the first daily
  visit to the public link and the payments registered, edited or reversed.
- **Conversions**: the responses of the delivery note, proforma and quote
  conversions expose `warnings` and `warning_codes`.
- **Contact imports**: `BusinessContactImport`, `BusinessContactImportError`
  and the `BusinessContactImportStatus` / `BusinessContactImportPreviewStatus`
  (`queued`, `running`, `completed`, `partial`, `failed`) and
  `BusinessContactImportPreviewResult` (`applied`, `skipped`, `failed`)
  enumerations; the preview rows gain `result`.
- Also: `DeclaracionResponsable` gains `components`, `producer_address` and
  `signature_types`, and `Error2` gains `line_index`.

### Changed — breaking

No operation disappears, but some generated classes follow the contract:

- `Operations\PublicApiV1InvoicesCreateResponseBody` is gone. The response of
  `publicApiV1InvoicesCreate()` is split by status:
  `PublicApiV1InvoicesCreateResponse::$twoHundredApplicationJsonObject`
  (`PublicApiV1InvoicesCreateResponseBody1`) and
  `$twoHundredAndOneApplicationJsonObject`
  (`PublicApiV1InvoicesCreateResponseBody2`) replace `$object`; both carry an
  `InvoiceWithCheckoutBlocks` in `data`.
- `CreateInvoiceRequest::$clientId` and `$seriesId` are now optional properties,
  so the constructor argument order changes: build the request with named
  arguments.
- The positional signature of `publicApiV1SeriesActive()` and
  `publicApiV1SeriesDefault()` changes, since `document_type` / `invoice_kind`
  come before `factuareaVersion`; use named arguments.
- Renamed classes: `Components\Payments` is now `Components\InvoicePayments`,
  `Components\SentVia` is `Components\InvoiceSentVia`,
  `Components\ResultEnum` is `Components\PurchaseScanEmailResult` and
  `Operations\DocumentType` is
  `Operations\PublicApiV1SeriesDefaultDocumentType`. `Invoice::$payments`,
  `Invoice::$sentVia`, `PurchaseScanEmail::$result` and the `documentType` of
  `PublicApiV1SeriesDefaultRequest` change type accordingly.
- `InvoiceActivity::$metadata` is a `Metadata` instead of an array.
- `PublicApiV1InvoicesCorrectiveResponseBody::$warnings` is now optional
  (`?array`).

### Notes

- The hand-written layer (`FactuareaClient`, `PageIterator`, `IdempotencyHook`,
  `FactuareaVersionHook`, `WebhookVerifier`) and its tests are unchanged.

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
