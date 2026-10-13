# Native CRM sales contracts

This handwritten extension belongs to the existing `factuarea/factuarea-php`
SDK. It reuses that SDK's `SDKConfiguration`, configured Guzzle client, native
API-key security or explicit OAuth dispatch source. It is not another SDK and
does not edit generated files. The pinned OpenAPI input predates recent CRM
surfaces; final export/generation, clean installation, PHP 8.2 execution and
release qualification remain separate pending work. CRM's current default
configuration is OFF/unratified. No installation or production activation is
claimed here.

```php
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\CreateLeadRequest;
use Factuarea\Sdk\Custom\Crm\SalesBatch81\WriteUnconfirmed;

// Server-only environment. Never render or log this credential.
$sdk = FactuareaClient::create(getenv('FACTUAREA_API_KEY'));
$sales = FactuareaClient::crmSales($sdk);
$intent = $sales->prepareLead(new CreateLeadRequest(name: $submittedName), $originalSubmissionKey);
$persistPrivately($intent->key, $intent->firstBody); // Durable host journal, before dispatch.
try {
    $lead = $sales->dispatch($intent);              // Exactly one HTTP call.
} catch (WriteUnconfirmed $failure) {
    $original = $sales->recover($intent);           // GET only; never another POST.
    // Continue only after the original is confirmed and current server admission permits the next step.
}
```

The host supplies a stable original step key. UTF-8 first body bytes, property
order, decimal strings, omitted fields and explicit nulls are frozen before
HTTP. The same `WriteIntent` cannot dispatch twice, including after 403, 409,
429, timeout, malformed success or 5xx. A redirect is never followed. This
extension does not invoke generated retry loops or after-error retry hooks.
General generated SDK retry behavior is not modified.

## Actual native routes

Paths below are relative to the existing SDK base `/v1`.

| Method | Path | Native operation | Fine scope | Custom entry point |
| --- | --- | --- | --- | --- |
| GET | `/crm/leads` | `crm_leads.index` | `crm_leads:read` | `listLeads` / `leads` |
| POST | `/crm/leads` | `crm_leads.create` | `crm_leads:write` | `prepareLead` then `dispatch` |
| PATCH | `/crm/leads/{lead}` | `crm_leads.update` | `crm_leads:write` | `prepareLeadPatch` then `dispatch` |
| POST | `/crm/opportunity` | `crm_opportunities.create` | `crm_opportunities:write` | `prepareOpportunity` then `dispatch` |
| POST | `/crm/crmactivity` | `crm_activities.create` | `crm_activities:write` | `prepareActivity` then `dispatch` |

This is the concrete supported subset, not a claim that every CRM operation is
now covered. Existing custom Meta, WhatsApp, original recovery, document and
CustomerService owners remain separate existing groups of this same SDK. A new
backend operation does not acquire a generated method from this file.

The server derives Company, actor, credential, environment, record access,
current C7, feature/plan admission and masks. The client supplies no authority,
entity UUID or effect UUID during create. One SDK client's prepared intent
cannot move to another SDK/credential context. Explicit native OAuth refresh
may supply a current descendant for a later read; the server proves ancestry,
current scopes and C7. An SDK token callback is never an authorization proof.

## Requests and representations

`CreateLeadRequest` supports manual `name`, `email`, `phone`, `mobile`,
`company_name`, `notes`, `owner_id`, `team_id`. At least one explicit name/email/
phone/mobile is required. It fixes native `source: manual`, without upstream
status or synthesized email. Address, metadata, governed typed Fields and other
Lead families are not exposed by this small DTO. Native validation is final.

`CreateOpportunityRequest` uses explicit title, existing pipeline/stage IDs,
amount as a string, currency, and original lead/contact reference. At least one
lead/contact UUID is required. No contact matching or customer promotion is
performed. The native amount input allows up to 16 integer digits and 4 decimal
places; exact input bytes are preserved. Native normalization may return 4
fractional places. The DTO never converts money to float. It checks the original runtime type
before assigning its string property, including when PHP callers omit
`strict_types`. The journey rejects a float before its first write.

`CreateActivityRequest` uses native type (`task`, `call`, `meeting`, `note`),
explicit title, target `{type,id}`, timezone, and optional body/owner/due_at.
The target is an existing contact/person/lead/opportunity/booking UUID. `due_at`
is an exact UTC timestamp; timezone is explicit. It does not create a second
Task, guess a timezone or promote a target.

`Documents\Omitted::Value` is reused from the SDK's existing native owner.
Optional omitted fields remain absent; explicit null is serialized as null.
`LeadPatchRequest` requires the actual positive `expected_version` and exposes
only the supported contact text fields. There is no native GET-original route
for this PATCH in this slice; `recover` explicitly refuses it rather than
inventing a route or resending PATCH. Inspect the actual current owner after
uncertainty and preserve the original host intent.

`SalesResult` preserves full native JSON/raw response and forward-compatible
fields/status values. `field(name)` returns the original value, explicit null,
or `Omitted::Value` when masked/absent. Returned identities are actual UUID
strings. Historical User owner UUIDv4/v7 remains distinct from local UUIDv7.
An identity-only confirmed Lead receipt has no invented version or detail.
An Activity pending/in_progress/unconfirmed result stops the journey; its
identity does not announce a confirmed write.

## Pagination and original recovery

Lead listing uses the current owner envelope
`{data:{data:[...],next_cursor,has_more}}`, not BottleCRM's open/closed sections.
`leads(maximumPages, limit)` follows every original opaque cursor with an
explicit page budget. Repeated/missing/inconsistent cursors or exhaustion fail
visibly rather than pretending a partial first page was complete. The iterator
can have yielded earlier rows before a later failure; callers must distinguish
iteration completion from partial collection.

After a process restart, the host can reconstruct only an
`OAuthOriginals\OriginalIntent` from its private journal's original operation,
key and exact first body, then call `recoverOriginal`. This is a read, not a
new write intent or credential-family claim. It reuses the existing four native
original GET contracts for Lead create, Opportunity create/stage and Activity
create. Only Opportunity stage includes its original target UUID.

Original lookup sends the exact original `Idempotency-Key` header and
`original_body_sha256` query, with no body. Confirmed receipts retain the
original 2xx status and exact response body string. `pending`, `in_progress`,
`unconfirmed` and `missing` carry null original response and do not authorize a
resend. A foreign body hash or fabricated confirmation fails closed. Original
receipts are historical results, not current owner state or current grants.

## Errors and examples

`ValidationFailure`, `ConflictFailure`, `PermissionFailure`, `RateLimitFailure`
and `WriteUnconfirmed` extend `CrmFailure`. They retain HTTP status, bounded
machine code/subcode/field param, request ID and Retry-After as a string. The
request ID comes from `X-Request-Id`, with a validated `error.request_id`
fallback when that header is absent. They do not retain raw provider error body,
human message, credential, first request body or previous exception. Unknown
error bodies use a closed generic code.
No error causes an automatic write retry. A current revocation after dispatch
can remain uncertain even when its error type is permission denial.

`examples/CrmWebsite.php` obtains the actual API key only from the server
`FACTUAREA_API_KEY` environment and requires the host to persist the exact
original before one dispatch. Form authentication/CSRF/rate policy belongs to
the host; this sample does not replace those controls or log form data.

`examples/CrmJourney.php` composes Lead→Opportunity→Activity using only the
server-returned UUIDs and three explicit original keys. The initial journey
stops at the first denied/uncertain step. The host recovers its journaled
originals and resumes only the affected step with the native methods; it must
not call the entire initial journey again. Confirmed earlier steps are not
rolled back, re-created or silently converted into customers.

Offline Guzzle tests run only this owner group. They do not prove backend
installation, live credentials, runtime ACL/tenant enforcement, provider
approval, generated inventory completeness or a published package. These
remain required release evidence.
