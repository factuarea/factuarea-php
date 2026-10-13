# CRM document contracts (incremental, unpublished)

This hand-written delivery consumes the current Factuarea backend source contract. It does not change the pinned OpenAPI document, generated SDK, package version or publication status. The server still controls feature gates, effective Company, sandbox, current ACL/scopes and private document access.

No repository-local AGENTS.md or CLAUDE.md was found. The native SDK baseline is `f21efe032175a38481e20c296268148e7c670a6d`; `docs/REGENERATION.md` keeps maintained code under `src/Custom/`. Existing `FactuareaClient::create()` and `Factuarea::builder()` remain compatible.

```php
use Factuarea\Sdk\Custom\FactuareaClient;
use Factuarea\Sdk\Custom\Crm\Documents\Request\LinkOpportunityDocumentRequest;

$sdk = FactuareaClient::create(getenv('FACTUAREA_API_KEY'));
$library = FactuareaClient::libraryDocuments($sdk);
$opportunityDocuments = FactuareaClient::opportunityDocuments($sdk);

// Obtain the original key from durable application state before sending.
$result = $opportunityDocuments->linkDocument(
    $opportunityId,
    new LinkOpportunityDocumentRequest('quote', $nativeQuoteId, false),
    $originalKey,
);
```

Both clients use the same configured SDK HTTP client, security and before-request hooks. They create no second HTTP client. Version and managed-profile headers use the existing optional arguments; Company and environment are never body fields. Secret keys stay in the host credential store.

## Current operations

Paths below are relative to the configured `/v1` server URL. Library reads use `crm_documents:read`, writes use `crm_documents:write`. Opportunity document reads/candidates use `crm_opportunitydocuments:read`, writes use `crm_opportunitydocuments:write`.

| Client method | Native method/path | Request/response |
| --- | --- | --- |
| library `listDocuments`, `iterateDocuments` | GET `/crm/documents` | Native page/per_page/search/sort/archived; typed masked page |
| library `getDocument` | GET `/crm/documents/{document}` | Typed masked document |
| library `uploadDocument` | POST `/crm/documents` | Current JSON title/producer_reference_id/claimed_mime; original 201 id/version receipt |
| library `replaceVersion` | POST `/crm/documents/{document}/versions` | expected_version/producer_reference_id/claimed_mime |
| library `shareDocument` | POST `/crm/documents/{document}/shares` | expected_version/grant_type/grantee_id/optional expires_at |
| library `revokeShare` | POST `/crm/documents/{document}/shares/revoke` | expected_version/share_id |
| library `archiveDocument` | POST `/crm/documents/{document}/archive` | expected_version |
| library `downloadDocument` | GET `/crm/documents/{document}/download` | Optional pinned document_version; authenticated native byte stream |
| Opportunity `listDocuments`, `iterateDocuments` | GET `/crm/opportunitydocuments/{opportunity}/documents` | Typed filters JSON and opaque cursor; complete bounded iteration |
| Opportunity `listCandidates` | GET `/crm/opportunities/{opportunity}/documents/candidates` | Native owner type/search/cursor/revision and explicit availability |
| Opportunity `createQuote` | POST `/crm/opportunitydocuments/{opportunity}/quotes` | Closed native prefiscal recipient/draft; optional contact_id |
| Opportunity `linkDocument` | POST `/crm/opportunitydocuments/{opportunity}/documents` | document_type/document_id/is_primary |
| Opportunity `unlinkDocument` | DELETE `/crm/opportunitydocuments/{opportunity}/documents/{link}` | Association expected_version |
| Opportunity `financialSummary` | GET `/crm/opportunitydocuments/{opportunity}/financial-summary` | Separate currency buckets; forecast4/fiscal2 decimal strings |

Library upload currently registers an accredited native producer reference. `CommercialDocumentRequest::payload()` in the current backend accepts JSON only. Native multipart producer code exists, but its HTTP request/controller adapter is not yet connected. This SDK therefore exposes no fictional file-upload endpoint or multipart operation. Wiring and verifying that actual backend contract remains a Root dependency; multipart byte delivery will be added to these existing routes after that contract is implemented.

There is no public effect-id/receipt endpoint. `recover()` performs a single explicit request to the same existing mutation route with the immutable original body, key, CAS, version and effective profile. The backend chooses its private durable original receipt; recovery does not ask for the latest owner head. Library JSON producer-reference availability is checked by the native server; it is never inferred from a UUID.

## Uncertain writes and recovery

```php
use Factuarea\Sdk\Custom\Crm\Documents\DocumentWriteUnconfirmed;

try {
    $result = $opportunityDocuments->linkDocument($opportunityId, $body, $originalKey);
} catch (DocumentWriteUnconfirmed $error) {
    // Persist/retain the original intent in private application state.
    $originalIntent = $error->intent;
    // Once the application explicitly chooses recovery with original credentials:
    $result = $opportunityDocuments->recover($originalIntent);
}
```

Every write sends once, regardless of the generated SDK retry configuration. Timeout, native unconfirmed response, 5xx, unexpected success/redirect, malformed receipt, and post-dispatch 403/404 preserve the original intent. Typed native 409 CAS, 422 field and 429 quota errors retain the server error/request_id; the SDK never substitutes a key or dispatches automatically. After-error/success hooks cannot synthesize a receipt. Recovery is blocked if the server/credential binding or request authentication changes. The effective profile/version from the first request remains frozen even if decorating hooks later change.

Receipts require the exact original library identity and CAS+1, unlink association/CAS+1, and linked Opportunity/native owner identity/type. Linking an existing principal with `is_primary=false` may return its original `is_primary=true` and existing versions. Those server values are preserved. CRM/new link identifiers are UUIDv7; actual existing native owner UUIDs and spelling survive, including historical Quote UUIDs. Candidate revisions are read-only: the current link operation has no owner-revision request field.

`Omitted::Value` preserves omitted optional fields. Explicit null remains JSON null, including quote/contact and share expiry fields. Masked library fields remain omitted in typed responses. Fiscal amounts stay exact strings; non-fiscal contract/schedule amounts stay null with `not_applicable`. No currency conversion or fiscal calculation occurs in the SDK. Downloads expose authenticated bytes and native headers, without following redirects or producing public links.

## Original task mapping and evidence limits

This group implements CRM-083 portions of original `crm-php-sdk-delivery` tasks 1.1/1.3 (real SDK reconnaissance/native adaptation), 2.2 (existing methods), 2.3 (separate types/omission/null/decimal/date), 2.4 (native errors and unconfirmed intent), 2.5 (no-network HTTP contract tests), and CRM-144 task2.8 (exact available contracts and missing multipart dependency). `examples/CrmDocuments.php` is a composable typed subset of2.7. Hand-written incremental contracts do not complete2.1 generation/spec publication, the full Lead→Opportunity→Activity journey, clean installation/API sandbox A/B/real ACL and native producer certification,3.1 release evidence or4.x delivery gates.

This is native integration with no selected BottleCRM source copied. CRM-249/252/253 mapping uses current native backend request/resources/routes and no-network fixtures; upstream Python/ORM cases have no equivalent SDK runtime and are not claimed as PHP certification. No marketplace/package publication, production activation or Applied task credit is included.

The affected local test selector is `Tests/Custom/Crm/Documents/DocumentContractTest.php` plus the existing factory contract `Tests/Custom/Client/HttpBehaviourTest.php`. Runtime results and exact source hashes are handed to Root separately; this source document does not invent a successful execution.
