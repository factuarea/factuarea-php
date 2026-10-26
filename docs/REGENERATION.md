# Regenerating the Factuarea PHP SDK

This SDK is generated from a pinned OpenAPI document and assembled from two
clearly separated layers. This guide explains how to regenerate it, what is and
isn't safe to touch, and how to fall back to `openapi-generator` if Speakeasy is
ever unavailable.

---

## Layout: generated vs hand-written

| Path                     | Owner      | Regenerated? |
| ------------------------ | ---------- | ------------ |
| `src/` (except `Custom/`) | Speakeasy  | **Yes** — never edit by hand |
| `src/Custom/`            | maintainers | **No** — hand-written runtime helpers |
| `Tests/`                 | maintainers | **No** — hand-written tests |
| `spec/openapi.json`      | upstream   | replaced on spec update (pinned copy) |
| `.speakeasy/`            | Speakeasy + maintainers | workflow/overlays preserved; `gen.lock` managed |

The golden rule (design D5): **regeneration must never overwrite hand-written
code.** Speakeasy only writes files it tracks in `.speakeasy/gen.lock`. Anything
under `src/Custom/` and `Tests/` is untracked and therefore safe. The hand-written
runtime (webhook verifier, idempotency client, page iterator, client factory) lives
entirely in `src/Custom/` for this reason.

---

## The pinned spec

`spec/openapi.json` is a copy of the Factuarea public OpenAPI document (OpenAPI
3.1), exported with `php artisan public-api:export-spec --api=public-api` from
commit `0c2bc0e5fc` of the Factuarea API. It is committed so the SDK is
reproducible from this repo alone, and it is copied byte for byte (`cp`, no
reformatting, no trailing newline added).

| Pin | Value |
| --- | --- |
| Operations | **714** over **572** paths: 633 under `/companies/{company}`, 70 under `/accounts/{account}`, 11 at the root |
| SHA-256 of the file | `0219a93b32c6a223dd6c75b1b97371fa1ec3272ecca1f2da2c9d50b2ca55bf14` |
| Canonical SHA-256 (keys sorted recursively, `x-codeSamples` dropped, compact JSON) | `6ec087d0d39bb4cc62d2051c3a960f9d5d7c641c3ad3a4d0db9f28d75c40a44b` |

**`src/` is NOT regenerated from this pin yet.** It is the generation of `0.8.0`,
made from the 664-operation axis contract (SHA-256 `fcf93063…85848`, 652
generated methods). Against that contract the pin adds the **50
document-template operations**, all under `/companies/{company}/…`, and removes
none; 34 existing operations gain response statuses, parameters or a request
body, and 44 gain request-body fields. Until the regeneration the new operations
and fields are reachable with `RawRequest`; see
[Document-template contract](#document-template-contract-pending-regeneration-and-how-to-work-meanwhile)
for the details and the checks to run after regenerating. The pin also carries a
few changes that the production API already publishes and `0.8.0` was not
generated from: see [Changes already published by the API](#changes-already-published-by-the-api).

To update the SDK to a newer API version, replace `spec/openapi.json` with the new
pinned document and regenerate (below). Bump the version in
`.speakeasy/gen.yaml` (`php.version`) and update `CHANGELOG.md`.

---

## Regenerating with Speakeasy (primary)

Prerequisites:

- [Speakeasy CLI](https://www.speakeasy.com/docs/speakeasy-cli/getting-started)
  (`brew install speakeasy-api/homebrew-tap/speakeasy`).
- A Speakeasy API key for the `factuarea/factuarea` workspace, provided via the
  `SPEAKEASY_API_KEY` environment variable. **Never commit the key or paste it
  into any file.**

Run, from the repo root:

```bash
SPEAKEASY_API_KEY="…" speakeasy run --target all --auto-yes --skip-testing
```

This reads `.speakeasy/workflow.yaml`, applies the overlays (see below), and
regenerates `src/`, `docs/`, `composer.json`, `phpunit.xml`, etc. Then:

```bash
# Resolve dependencies on PHP 8.2 so dev deps stay 8.2-compatible (see note).
composer update
composer test
composer stan
```

### Overlay: drop `x-speakeasy-pagination`

`.speakeasy/overlays/drop-speakeasy-pagination.yaml` removes the
`x-speakeasy-pagination` extension from every operation before generation. The
public spec declares it only on the e-commerce store listings, and the Speakeasy
PHP generator turns it into a `next()` closure that re-calls the request
constructor with the raw parameter name (`starting_after: $nextCursor`) while
the generated request model exposes it camelCased (`$startingAfter`); PHPStan
then fails the generation with *Unknown parameter $starting_after* (Spec Sync
run of 2026-09-09). Every other cursor-paginated listing of this SDK carries no
extension and is iterated by the hand-written page iterator in `src/Custom/`,
which already follows `next_cursor`, so dropping the extension makes the store
listings behave like the rest. Remove the overlay (and its `overlays:` entry in
`.speakeasy/workflow.yaml`) once Speakeasy emits the camelCased argument.

### Overlay: retry status-code casing

`.speakeasy/overlays/retry-status-code-casing.yaml` lowercases each operation's
`x-speakeasy-retries.statusCodes` from `["429", "5XX"]` to `["429", "5xx"]`.

**Why:** the pinned spec declares the family code in uppercase (`5XX`, the OpenAPI
convention). The Speakeasy PHP retry runtime
(`src/Utils/Retry/RetryUtils::isRetryableResponse`, generator feature
`retries@0.1.1`) matches family codes with the case-sensitive regex
`/^[0-9]xx$/`, so an uppercase `5XX` in the generated retry-code array never
matches and **5xx responses would not be retried**. Lowercasing it via the
overlay makes backoff retries fire on 5xx as intended, **without modifying the
pinned source spec**.

This is an upstream Speakeasy runtime bug; if a future Speakeasy release makes the
regex case-insensitive, remove the overlay and its `overlays:` entry in
`.speakeasy/workflow.yaml`.

### Overlay: product options namespace

`.speakeasy/overlays/product-options-namespace.yaml` maps the new product-options
operation to `products.productOptions` for PHP generation. Speakeasy flattens
nested PHP groups into root classes: `products.options` would generate an
`Options` class that collides with its own `Utils\\Options` import and fails
PHPStan and PHP loading. The generated entry point is
`$sdk->products->productOptions`; the HTTP path, operation ID, tags and pinned
public spec remain unchanged. Keep this overlay on every spec-sync regeneration.

### Overlay: no retries on FACe

`.speakeasy/overlays/face-no-retry.yaml` removes `x-speakeasy-retries` from
`POST /companies/{company}/invoices/{invoice}/face-submissions` and
`POST /companies/{company}/face-submissions/{faceSubmission}/cancel`: a blind
retry could submit or cancel twice at FACe. It must stay after
`retry-status-code-casing.yaml` in `workflow.yaml`. The pinned contract no
longer declares `x-speakeasy-retries` on those two operations (they carry
`x-no-retry` instead), so against it both `remove` actions match nothing: the
overlay is a defensive no-op that keeps the retries off if the extension ever
comes back. Keep it until a regeneration confirms that the Speakeasy overlay
engine accepts a `remove` with no match; if it rejects it, delete the overlay
and its `workflow.yaml` entry, because the spec already carries the behaviour.
After regenerating, check that both generated methods no longer carry the `5xx`
retry codes.

Until then the **currently generated** methods do retry (`429` and `5xx`): the
`0.8.0` contract still declared the extension on them.
`Custom\Request\NoRetry::options()` disables the status-code retries per call
(the generated code ignores a per-call `Options::setRetryConfig()`: its `else`
branch rebuilds the default backoff, so only `retryCodes` is honoured). A dropped
connection is still retried, always with the same caller-supplied
`Idempotency-Key`, which the API answers by replaying the first outcome.

### Manual layer for field presence and extra headers

Every optional property of a generated request model carries `SkipWhenNull`, so
an unassigned field and a field assigned `null` both travel omitted. Where the
API treats `null` as "clear", use `Custom\Request\RequestOverrides::run()`
(`clear:` paths, `headers:`). Once Speakeasy emits a tri-state, remove it and
turn the incomplete test in `Tests/Custom/Request/PresenceWireTest.php` into a
real assertion.

### Notes on regenerated files

- **`composer.json`** is regenerated. Dev dependencies (`phpunit/phpunit`) allow
  PHPUnit 11/12/13; resolving on a **PHP 8.2** runtime naturally selects PHPUnit
  11 (8.2-compatible). The CI and local Docker commands run on PHP 8.2 for this
  reason — no hand-edit of `composer.json` is needed.
- **`phpunit.xml`** is regenerated to point its test suite at `./Tests`. The
  hand-written tests live in `Tests/` (capitalised) precisely so the regenerated
  config keeps finding them. Test generation is disabled
  (`generation.tests.generateTests: false` in `gen.yaml`).
- **`gen.yaml`** uses `versioningStrategy: manual` so the version is controlled
  explicitly (set `php.version`), instead of auto-bumping on every spec change.

---

## Document-template contract: pending regeneration and how to work meanwhile

**The Speakeasy regeneration of the pinned spec is PENDING.** It needs the
`SPEAKEASY_API_KEY` of the `factuarea/factuarea` workspace. Everything else is
ready: the pin, the overlays (all four checked against it, below) and the manual
layer on the company axis. Nothing under `src/` outside `src/Custom/` was
hand-edited.

### Regenerating this pin (maintainer with the Speakeasy key)

```bash
# 0. The pin, unchanged.
sha256sum spec/openapi.json
# 0219a93b32c6a223dd6c75b1b97371fa1ec3272ecca1f2da2c9d50b2ca55bf14

# 1. Public methods of the generated layer BEFORE (src/*.php: neither src/Custom nor src/Hooks).
grep -o 'public function publicApi[A-Za-z0-9]*' src/*.php \
  | sed -E 's#^src/([A-Za-z0-9]+)\.php:public function #\1::#' | LC_ALL=C sort > /tmp/methods-before.txt
wc -l < /tmp/methods-before.txt      # 652

# 2. Set the release version in .speakeasy/gen.yaml (php.version) BEFORE
#    generating: the generator writes it into SDKConfiguration and the User-Agent.

# 3. Regenerate (workflow.yaml applies the four overlays in order).
SPEAKEASY_API_KEY="…" speakeasy run --target all --auto-yes --skip-testing
sha256sum spec/openapi.json          # must be unchanged by the generator

# 4. Public methods AFTER, and the diff.
grep -o 'public function publicApi[A-Za-z0-9]*' src/*.php \
  | sed -E 's#^src/([A-Za-z0-9]+)\.php:public function #\1::#' | LC_ALL=C sort > /tmp/methods-after.txt
diff /tmp/methods-before.txt /tmp/methods-after.txt

# 5. Dependencies and gates (PHP 8.2, see the note on composer.json).
composer update && composer dump-autoload -o
composer test
composer stan
vendor/bin/phpstan analyse src/Custom --level=8 --memory-limit=2g --no-progress
vendor/bin/phpstan analyse examples/document-templates.php --level=8 --memory-limit=2g --no-progress
vendor/bin/pint --test src/Custom Tests examples
git diff --stat -- src/Custom Tests examples   # manual layer untouched
```

The diff of step 4 must be **exactly 50 added lines and no removed line** (702
methods): one `Class::method` per row of the table below, with the class of its
group (`PdfTemplates`, `PdfCommercialIdentities`, `PdfTemplatePreviews`,
`PdfArtifacts`, `AdministrativeRevisions`, `Invoices`, `Quotes`, `Proformas`,
`DeliveryNotes`, `Series`). The 12 seat offer redemption operations stay
ungenerated (`0.8.0` *Known gaps*). A method in the diff without its operation in
the table is an upstream problem: fix the contract, never rename in the SDK.

### What the manual layer (`src/Custom/`, protected) offers meanwhile

| File | Purpose |
| --- | --- |
| `Request/RequestOverrides.php`, `Request/RequestOverridesClient.php` | Per-call extra headers (`If-Match`, `If-None-Match`) and explicit JSON `null` on a generated method. |
| `Response/ResponseMeta.php` | Status, `ETag`, `Retry-After` and JSON body of any response or of an `APIException` (202, 200 replay, 503). |
| `Request/RawRequest.php` | Calls an operation without a typed method (or with a request field a generated model cannot carry) through the SDK's own authenticated client: URL, `Accept`, `User-Agent`, `Factuarea-Version`, automatic `Idempotency-Key`, active `RequestOverrides`. The company `id` is one more path parameter (`/companies/{company}/…`). No retries; never throws on an HTTP status. |
| `Request/NoRetry.php` | `Options` that disable the generated `429`/`5xx` retries per call (FACe). |
| `FactuareaClient.php`, `Idempotency/*`, `Pagination/*`, `Version/*`, `Webhooks/*` | Unchanged. |

After any regeneration these files and `Tests/Custom/` must be untouched
(`git diff --stat -- src/Custom Tests` is empty apart from intended edits).

Presence is handled by `RequestOverrides::run(clear: [...])`, which names the
exact JSON paths sent as `null`, never guesses presence and lives in the
protected layer. A presence sentinel plus a normalizer rewriting every generated
request model would fork the generator output and be overwritten by the next
regeneration, and a second local runner would only duplicate `speakeasy run` with
the committed `workflow.yaml`.

### Operations that still have no typed method (50)

Call them with `RawRequest` (see `examples/document-templates.php` and the
README), passing the company `id` as the `{company}` path parameter. The last two
columns are the name and the call shape the generator should produce. They are
derived from the rules it applied to every one of the 652 methods of `0.8.0`
(the method is the camelCased `operationId`; parameters plus body above
`maxMethodParams: 4` make an `Operations\…Request` object, otherwise the
arguments are positional: required body first, then required parameters, then
optional ones, in spec order). They are **not verified** until the regeneration:
the classes do not exist yet.

Their payloads have no generated model yet either, so the fields travel as plain
JSON through `RawRequest`. A commercial-identity revision carries
`logo_dark_asset_id`, `brand_image_dark_asset_id`, `dark_background_fallback`
(`silhouette` | `plate`) and `brand_size` (`small` | `medium` | `large`). `create`
and `publish_revision` accept them, and so does the unsaved identity of a
template preview. That unsaved identity also requires its six base keys, which
may be `null`: `commercial_name`, `logo_asset_id`, `brand_image_asset_id`,
`brand_mode`, `contact_email` and `contact_phone`. A brand holds no postal
address. A template's `branding.size` also accepts `inherit`, and
`sections.notes_mode` prints the document notes with `auto` and hides them with
`no` (`yes` is still accepted and behaves like `auto`).

| Operation | `operationId` | Expected typed method | Expected call shape |
| --- | --- | --- | --- |
| `GET /companies/{company}/delivery-notes/{delivery_note}/presentation` | `public-api.v1.delivery_notes.get_presentation` | `$sdk->deliveryNotes->publicApiV1DeliveryNotesGetPresentation` | ($company, $deliveryNote, ?$factuareaVersion) |
| `PATCH /companies/{company}/delivery-notes/{delivery_note}/presentation` | `public-api.v1.delivery_notes.assign_presentation` | `$sdk->deliveryNotes->publicApiV1DeliveryNotesAssignPresentation` | `Operations\PublicApiV1DeliveryNotesAssignPresentationRequest` |
| `GET /companies/{company}/delivery-notes/{delivery_note}/presentation-history` | `public-api.v1.delivery_notes.list_presentations` | `$sdk->deliveryNotes->publicApiV1DeliveryNotesListPresentations` | `Operations\PublicApiV1DeliveryNotesListPresentationsRequest` |
| `POST /companies/{company}/delivery-notes/{delivery_note}/presentation-revisions` | `public-api.v1.delivery_notes.revise_presentation` | `$sdk->deliveryNotes->publicApiV1DeliveryNotesRevisePresentation` | `Operations\PublicApiV1DeliveryNotesRevisePresentationRequest` |
| `GET /companies/{company}/invoices/{invoice}/administrative-revisions` | `public-api.v1.invoices.administrative_revisions.index` | `$sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsIndex` | ($company, $invoice, ?$factuareaVersion) |
| `POST /companies/{company}/invoices/{invoice}/administrative-revisions` | `public-api.v1.invoices.administrative_revisions.store` | `$sdk->invoices->administrativeRevisions->publicApiV1InvoicesAdministrativeRevisionsStore` | `Operations\PublicApiV1InvoicesAdministrativeRevisionsStoreRequest` |
| `GET /companies/{company}/invoices/{invoice}/presentation` | `public-api.v1.invoices.get_presentation` | `$sdk->invoices->publicApiV1InvoicesGetPresentation` | ($company, $invoice, ?$factuareaVersion) |
| `PATCH /companies/{company}/invoices/{invoice}/presentation` | `public-api.v1.invoices.assign_presentation` | `$sdk->invoices->publicApiV1InvoicesAssignPresentation` | `Operations\PublicApiV1InvoicesAssignPresentationRequest` |
| `GET /companies/{company}/invoices/{invoice}/presentation-history` | `public-api.v1.invoices.list_presentations` | `$sdk->invoices->publicApiV1InvoicesListPresentations` | `Operations\PublicApiV1InvoicesListPresentationsRequest` |
| `POST /companies/{company}/invoices/{invoice}/presentation-revisions` | `public-api.v1.invoices.revise_presentation` | `$sdk->invoices->publicApiV1InvoicesRevisePresentation` | `Operations\PublicApiV1InvoicesRevisePresentationRequest` |
| `POST /companies/{company}/pdf-artifacts` | `public-api.v1.pdf_artifacts.prepare` | `$sdk->pdfArtifacts->publicApiV1PdfArtifactsPrepare` | ($body, $company, $idempotencyKey, ?$factuareaVersion) |
| `GET /companies/{company}/pdf-artifacts/{artifact}` | `public-api.v1.pdf_artifacts.show` | `$sdk->pdfArtifacts->publicApiV1PdfArtifactsShow` | ($company, $artifact, ?$factuareaVersion) |
| `GET /companies/{company}/pdf-artifacts/{artifact}/download` | `public-api.v1.pdf_artifacts.download` | `$sdk->pdfArtifacts->publicApiV1PdfArtifactsDownload` | ($company, $artifact, ?$ifNoneMatch, ?$factuareaVersion) |
| `POST /companies/{company}/pdf-artifacts/{artifact}/render` | `public-api.v1.pdf_artifacts.render` | `$sdk->pdfArtifacts->publicApiV1PdfArtifactsRender` | `Operations\PublicApiV1PdfArtifactsRenderRequest` |
| `GET /companies/{company}/pdf-commercial-identities` | `public-api.v1.pdf_commercial_identities.list` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesList` | `Operations\PublicApiV1PdfCommercialIdentitiesListRequest` |
| `POST /companies/{company}/pdf-commercial-identities` | `public-api.v1.pdf_commercial_identities.create` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesCreate` | ($body, $company, ?$idempotencyKey, ?$factuareaVersion) |
| `GET /companies/{company}/pdf-commercial-identities/{identity}` | `public-api.v1.pdf_commercial_identities.find` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesFind` | ($company, $identity, ?$factuareaVersion) |
| `PATCH /companies/{company}/pdf-commercial-identities/{identity}` | `public-api.v1.pdf_commercial_identities.update` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesUpdate` | `Operations\PublicApiV1PdfCommercialIdentitiesUpdateRequest` |
| `POST /companies/{company}/pdf-commercial-identities/{identity}/archive` | `public-api.v1.pdf_commercial_identities.archive` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesArchive` | `Operations\PublicApiV1PdfCommercialIdentitiesArchiveRequest` |
| `GET /companies/{company}/pdf-commercial-identities/{identity}/impact` | `public-api.v1.pdf_commercial_identities.impact` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesImpact` | ($company, $identity, $action, ?$factuareaVersion) |
| `POST /companies/{company}/pdf-commercial-identities/{identity}/restore` | `public-api.v1.pdf_commercial_identities.restore` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRestore` | `Operations\PublicApiV1PdfCommercialIdentitiesRestoreRequest` |
| `POST /companies/{company}/pdf-commercial-identities/{identity}/revisions` | `public-api.v1.pdf_commercial_identities.publish_revision` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesPublishRevision` | `Operations\PublicApiV1PdfCommercialIdentitiesPublishRevisionRequest` |
| `GET /companies/{company}/pdf-commercial-identities/{identity}/revisions/{revision}` | `public-api.v1.pdf_commercial_identities.revision` | `$sdk->pdfCommercialIdentities->publicApiV1PdfCommercialIdentitiesRevision` | ($company, $identity, $revision, ?$factuareaVersion) |
| `POST /companies/{company}/pdf-template-previews` | `public-api.v1.pdf_template_previews.create` | `$sdk->pdfTemplatePreviews->publicApiV1PdfTemplatePreviewsCreate` | ($body, $company, $idempotencyKey, ?$factuareaVersion) |
| `GET /companies/{company}/pdf-templates` | `public-api.v1.pdf_templates.list` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesList` | `Operations\PublicApiV1PdfTemplatesListRequest` |
| `POST /companies/{company}/pdf-templates` | `public-api.v1.pdf_templates.create` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesCreate` | ($body, $company, ?$idempotencyKey, ?$factuareaVersion) |
| `POST /companies/{company}/pdf-templates/assets` | `public-api.v1.pdf_templates.asset_upload` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesAssetUpload` | ($body, $company, $idempotencyKey, ?$factuareaVersion) |
| `GET /companies/{company}/pdf-templates/assets/{asset}` | `public-api.v1.pdf_templates.asset_get` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesAssetGet` | ($company, $asset, ?$factuareaVersion) |
| `GET /companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}` | `public-api.v1.pdf_templates.contact_default_get` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultGet` | `Operations\PublicApiV1PdfTemplatesContactDefaultGetRequest` |
| `PATCH /companies/{company}/pdf-templates/contact-defaults/{contact}/{document_type}/{purpose}` | `public-api.v1.pdf_templates.contact_default_set` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesContactDefaultSet` | `Operations\PublicApiV1PdfTemplatesContactDefaultSetRequest` |
| `GET /companies/{company}/pdf-templates/defaults` | `public-api.v1.pdf_templates.defaults_get` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsGet` | ($company, ?$factuareaVersion) |
| `PATCH /companies/{company}/pdf-templates/defaults` | `public-api.v1.pdf_templates.defaults_set` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesDefaultsSet` | `Operations\PublicApiV1PdfTemplatesDefaultsSetRequest` |
| `GET /companies/{company}/pdf-templates/{template}` | `public-api.v1.pdf_templates.find` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesFind` | ($company, $template, ?$factuareaVersion) |
| `PATCH /companies/{company}/pdf-templates/{template}` | `public-api.v1.pdf_templates.update` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesUpdate` | `Operations\PublicApiV1PdfTemplatesUpdateRequest` |
| `POST /companies/{company}/pdf-templates/{template}/archive` | `public-api.v1.pdf_templates.archive` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesArchive` | `Operations\PublicApiV1PdfTemplatesArchiveRequest` |
| `POST /companies/{company}/pdf-templates/{template}/duplicate` | `public-api.v1.pdf_templates.duplicate` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesDuplicate` | `Operations\PublicApiV1PdfTemplatesDuplicateRequest` |
| `GET /companies/{company}/pdf-templates/{template}/impact` | `public-api.v1.pdf_templates.impact` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesImpact` | ($company, $template, $action, ?$factuareaVersion) |
| `POST /companies/{company}/pdf-templates/{template}/restore` | `public-api.v1.pdf_templates.restore` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesRestore` | `Operations\PublicApiV1PdfTemplatesRestoreRequest` |
| `POST /companies/{company}/pdf-templates/{template}/revisions` | `public-api.v1.pdf_templates.publish_revision` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesPublishRevision` | `Operations\PublicApiV1PdfTemplatesPublishRevisionRequest` |
| `GET /companies/{company}/pdf-templates/{template}/revisions/{revision}` | `public-api.v1.pdf_templates.revision` | `$sdk->pdfTemplates->publicApiV1PdfTemplatesRevision` | ($company, $template, $revision, ?$factuareaVersion) |
| `GET /companies/{company}/proformas/{proforma}/presentation` | `public-api.v1.proformas.get_presentation` | `$sdk->proformas->publicApiV1ProformasGetPresentation` | ($company, $proforma, ?$factuareaVersion) |
| `PATCH /companies/{company}/proformas/{proforma}/presentation` | `public-api.v1.proformas.assign_presentation` | `$sdk->proformas->publicApiV1ProformasAssignPresentation` | `Operations\PublicApiV1ProformasAssignPresentationRequest` |
| `GET /companies/{company}/proformas/{proforma}/presentation-history` | `public-api.v1.proformas.list_presentations` | `$sdk->proformas->publicApiV1ProformasListPresentations` | `Operations\PublicApiV1ProformasListPresentationsRequest` |
| `POST /companies/{company}/proformas/{proforma}/presentation-revisions` | `public-api.v1.proformas.revise_presentation` | `$sdk->proformas->publicApiV1ProformasRevisePresentation` | `Operations\PublicApiV1ProformasRevisePresentationRequest` |
| `GET /companies/{company}/quotes/{quote}/presentation` | `public-api.v1.quotes.get_presentation` | `$sdk->quotes->publicApiV1QuotesGetPresentation` | ($company, $quote, ?$factuareaVersion) |
| `PATCH /companies/{company}/quotes/{quote}/presentation` | `public-api.v1.quotes.assign_presentation` | `$sdk->quotes->publicApiV1QuotesAssignPresentation` | `Operations\PublicApiV1QuotesAssignPresentationRequest` |
| `GET /companies/{company}/quotes/{quote}/presentation-history` | `public-api.v1.quotes.list_presentations` | `$sdk->quotes->publicApiV1QuotesListPresentations` | `Operations\PublicApiV1QuotesListPresentationsRequest` |
| `POST /companies/{company}/quotes/{quote}/presentation-revisions` | `public-api.v1.quotes.revise_presentation` | `$sdk->quotes->publicApiV1QuotesRevisePresentation` | `Operations\PublicApiV1QuotesRevisePresentationRequest` |
| `GET /companies/{company}/series/{series}/pdf-template` | `public-api.v1.series.get_pdf_template` | `$sdk->series->publicApiV1SeriesGetPdfTemplate` | ($company, $series, ?$factuareaVersion) |
| `PATCH /companies/{company}/series/{series}/pdf-template` | `public-api.v1.series.assign_pdf_template` | `$sdk->series->publicApiV1SeriesAssignPdfTemplate` | `Operations\PublicApiV1SeriesAssignPdfTemplateRequest` |

### Existing methods whose signature changes on regeneration (11)

The pin adds a request body or a header parameter to operations that already have
a typed method. By the same rules, the regeneration should change these
signatures, which breaks positional callers (record it in the release notes):

- **Positional → request object** (`Operations\…Request`), because the operation
  gains an optional request body: `Invoices::publicApiV1InvoicesIssue`,
  `InvoicesFaceSubmissions::publicApiV1InvoicesFaceSubmissionsSubmit` and the four
  `…Duplicate` methods of `Invoices`, `Quotes`, `Proformas` and `DeliveryNotes`.
- **Positional → request object**, because the operation gains the
  `If-None-Match` header: `Quotes::publicApiV1QuotesPdf`,
  `Proformas::publicApiV1ProformasPdf` and `DeliveryNotes::publicApiV1DeliveryNotesPdf`
  (`Invoices::publicApiV1InvoicesPdf` already takes a request object, which gains
  `ifNoneMatch`).
- **A new optional argument before `$factuareaVersion`**:
  `Invoices::publicApiV1InvoicesFacturae` (`?string $submissionId`) and
  `TaxCatalog::publicApiV1TaxCatalogShow` (`?string $ifNoneMatch`, first argument).

No other method changes its signature. `Quotes::publicApiV1QuotesAccept` and
`Proformas::publicApiV1ProformasAccept` already take a request object: the pin
only adds two optional fields, `presentation_precondition` and
`expected_presentation_uuid` (the retained capture that was reviewed; if it is no
longer the current one the acceptance is refused with `409` and nothing is
written), to `Components\AcceptQuoteRequest` and `Components\AcceptProformaRequest`.

After the regeneration, the request bodies of `issue`, `duplicate` and FACe
`submit`, the `If-None-Match` of the downloads and the tax catalog, and the
`submission_id` of Facturae no longer need `RawRequest` or `RequestOverrides`.

Until then, the 44 existing operations whose request body gains fields go through
`RawRequest` when the caller needs those fields: the presentation preconditions
(`presentation_precondition(s)`, `expected_selection_version`,
`expected_source_token`, `expected_presentation_uuid`, …) of `send`, `issue`,
`duplicate`, `convert`, `sign`, the bulk actions and `quotes`/`proformas`
`accept`; `pdf_template_id` on documents and series; payment terms and recurring
presentation policies on `create`/`update`; the administrative centres, business
references, invoicing period and legal literals of `invoices.create`;
`dir3_proponent` of the customer profile; and the public link `publish` action.
`stripe_autoinvoicing.accounts.update` also gains the `fee_expenses_*` fields that
the API already publishes (below), 45 operations in total.
`quarterly.download_zip` `include_non_invoiced_charges` needs it to send `null`.
The generated `pdf_link` method rejects the second `202` body of the contract (it
has no `pdf_path`) with a `PropertyMissingException`: read that endpoint with
`RawRequest`.

### Statuses the generated methods do not expect

A status a generated method does not declare raises `APIException` even when it
is a success. Read it with `ResponseMeta::fromException()`:

- `202` JSON from the four `.../pdf` downloads (invoices, quotes, proformas,
  delivery notes): the PDF is still rendering, or a failed render that can be
  retried was claimed again (`failed_render_claimed_again`); `Retry-After` when
  present.
- `200` from FACe submit: replay of an already accepted submission.
- `428`, `409`, `410`, `412`, `422`, `500`, `502`, `503` on the changed operations
  arrive as `APIException` (generic 4XX/5XX branch) or `ErrorThrowable` (declared
  ones); the status and body are available in both. `idempotency_key_reused` is a
  `422`. Many document writes and downloads declare `409`
  `pdf_presentation_conflict` and `pdf_presentation_context_changed`: reread the
  presentation and retry with its current state. `invoices.create`,
  `invoices.issue`, `invoices.schedule` and `invoices.reschedule` declare `422`
  `invoice_due_date_not_last_instalment`: with instalments the invoice falls due
  on the last one, and issuing or scheduling it with another `due_on` (or none)
  is refused.
- `500` with `pdf_original_unavailable`, `pdf_original_corrupt` or
  `pdf_original_layout_unavailable` on the PDF operations (downloads, `pdf-link`,
  presentation revisions, previews, artifact `prepare`, `render` and `download`):
  the stored original cannot be served. `pdf_original_layout_unavailable` means
  the original is intact but this API version no longer serves its layout; it is
  a platform failure, so retrying does not help: contact support.
- `503 pdf_artifact_not_ready` with `Retry-After` on the four `.../pdf` downloads,
  `pdf-link`, previews, `prepare` and `render`: another request is still preparing
  the same PDF and nothing was created. The generated download methods retry it
  with their 5XX backoff; `RawRequest` never retries, so read
  `ResponseMeta::fromResponse($response)->retryAfter` and repeat the same request
  with the same `Idempotency-Key`.

### Overlays against the pin

`workflow.yaml` applies, in this order: `retry-status-code-casing.yaml`,
`product-options-namespace.yaml`, `drop-speakeasy-pagination.yaml`,
`face-no-retry.yaml`. Matched nodes in the pinned spec:

| Overlay | Target | Matches |
| --- | --- | --- |
| `retry-status-code-casing.yaml` | `$.paths[*][*].x-speakeasy-retries` (and its `statusCodes`) | 712 of 714 operations (the two FACe operations declare none) |
| `product-options-namespace.yaml` | `GET /companies/{company}/products/{product}/options` | 1 |
| `drop-speakeasy-pagination.yaml` | `$.paths[*][*].x-speakeasy-pagination` | 1 (`GET /companies/{company}/stores`) |
| `face-no-retry.yaml` | `x-speakeasy-retries` of the two FACe operations | 0 (the operations exist; the extension does not, see above) |

No new overlay is needed by the document-template operations as far as static
analysis can tell. The new groups are `pdfTemplates`, `pdfCommercialIdentities`,
`pdfTemplatePreviews`, `pdfArtifacts` and the nested
`invoices.administrativeRevisions`, which Speakeasy flattens into an
`AdministrativeRevisions` class. No generated class has any of those names, and
none matches a short name the generated classes import (`Components`,
`DeserializationContext`, `HookContext`, `LocalDate`, `Operations`, `Options`,
`Retry`, `RetryConfig`, `RetryUtils`). Still, check for a PHP class-name clash
after the first run, as happened with `products.options`.

### Changes already published by the API

The pin also carries API changes that are already live in production but that
`0.8.0` was not generated from. They are not part of the document-template
contract:

- Connected Stripe accounts: `ConnectedAccount` gains `fee_expenses_enabled`,
  `fee_expenses_tax_treatment` and `fee_expenses_since` (always present in the
  response; the last two may be `null`), `UpdateConnectedAccountRequest` accepts
  them, and `connection_method` gains the value `platform`. Turning on
  `subscription_autoinvoicing_enabled` for a `platform` account is a `422`
  `parameter_invalid_value` with subcode
  `platform_account_subscriptions_owned_by_billing`.
- Payouts: `StripePayout.composition` gains `refunds_total`,
  `standalone_fee_total` and `other_total`, also in the `payout.reconciled` event.
- `PurchaseInvoice`: the descriptions of `total` and `taxes_total` cover
  self-assessed VAT.

The generated enums are strict, and the `ConnectionMethod` enum of `0.8.0` has no
`platform` case: reading a connected account linked as `platform` fails to
deserialize until the regeneration, so read it with `RawRequest` meanwhile. The
open automatic spec-sync pull request of `main` (#23) carries these same changes.
If it is merged first, merge `main` into this branch and regenerate once from
this pin; the changes then belong to that release's changelog entry.

### After the regeneration

In this order: (a) turn the typed methods that now exist into the call path of
`examples/document-templates.php` and the README, keeping `RawRequest` only for
what is still missing (it stays as the escape hatch); (b) rewrite
`Tests/Custom/Client/DocumentTemplateContractTest.php` against the generated
methods, keeping the same wire assertions; (c) re-check the retry codes of the
two FACe methods and remove `NoRetry` from the docs if the overlay or the spec made
it redundant; (d) the incomplete test in `Tests/Custom/Request/PresenceWireTest.php`
when Speakeasy emits a tri-state; (e) the generated enums are strict (`from()`; an
unknown value fails as `PropertyMissingException`): FACe `status` is an enum in
the pinned contract (`prepared`, `transmission_uncertain`, `submitted`,
`registered_rcf`, `accounted`, `paid`, `rejected`, `cancellation_requested`,
`cancelled`, `error`), so a status added later will break a deserialization until
the SDK is regenerated; (f) move the `[Unreleased]` entry of `CHANGELOG.md` to
the released version, with the signature changes listed above.

### Client flow coverage (CE01-CE25)

`Tests/Custom/Client/DocumentTemplateContractTest.php` covers, with a mocked
transport, the wire (method, path, query, headers, body, `null` versus omitted
versus `[]`), the `ETag`/`If-Match`/`If-None-Match` round trips, `Idempotency-Key`,
the `202`/`304`/`503`/`409`/`410`/`428` handling and the FACe single call for:
CE01-CE06, CE08, CE09, CE11, CE12, CE14-CE17, CE21-CE25 (the document-template
operations through `RawRequest`, the existing ones through their generated
methods). Not repeated, with the reason:

- CE02, CE07, CE10, CE13: server rules (brand fields, example/draft authority,
  line UUIDs, recurrence policies) over the same request shapes already measured
  (`RawRequest` sends the body unchanged; nothing in the SDK interprets it).
- CE18, CE19: public links and the Stripe return are browser URLs, not SDK
  operations; the SDK builds no URL of its own.
- CE20: `sign` keeps its generated model; its `forget` counterpart (CE21) is
  covered. Roles, tenants, privacy and quota outcomes (the 401/403/404 matrix per
  actor) are server behaviour: the SDK test only proves each status surfaces once.
- CE23 partial rows and CE25 paging by cursor: the SDK relays the headers and
  bytes; there is no client-side aggregation.

## Fallback: openapi-generator

If Speakeasy is unavailable, the SDK can be regenerated with the open-source
[OpenAPI Generator](https://openapi-generator.tech/) `php` target. The output
shape differs (Guzzle-coupled, less idiomatic), so this is an emergency path; the
hand-written `src/Custom/` layer is generator-independent and keeps working.

1. Generate into a scratch directory (Docker, no host install required):

   ```bash
   docker run --rm -v "$PWD":/local openapitools/openapi-generator-cli generate \
     -i /local/spec/openapi.json \
     -g php \
     -o /local/.openapi-generator-out \
     --additional-properties=invokerPackage=Factuarea\\Sdk,packageName=factuarea/factuarea-php,composerVendorName=factuarea,composerPackageName=factuarea-php,variableNamingConvention=camelCase
   ```

2. Review the generated client surface and reconcile method names against the
   [SDK method naming contract](sdks/) (`operationId → method`). OpenAPI
   Generator names methods differently from Speakeasy, so consumers' call sites
   would change — treat a generator switch as a **major** SemVer bump and
   document it in `CHANGELOG.md`.

3. Keep `src/Custom/` intact and re-point its imports if the generated namespace
   for `Security`, hooks or the client builder changes. The custom helpers depend
   only on:
   - PSR-7 `RequestInterface` / `ResponseInterface` (idempotency client, page iterator),
   - PHP's `hash_hmac` / `hash_equals` (webhook verifier),
   so most of the runtime survives a generator change untouched. Only
   `src/Custom/FactuareaClient.php` (which references the generated builder and
   `Security`) needs adjusting.

4. Run `composer test` and `composer stan` before publishing.

Because the spec carries vendor-neutral metadata (`x-speakeasy-*` are ignored by
other generators; pagination/retry semantics are also implemented in
`src/Custom/`), the SDK remains regenerable by any OpenAPI generator.
