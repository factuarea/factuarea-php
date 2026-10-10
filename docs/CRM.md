# Native CRM source extension

`Custom\Crm\CrmClient` adds the current ContactPeople, Lead, Pipeline, KnowledgeBase and PublicHelpCenter administration operations to an existing configured `Factuarea` instance. It shares that instance's HTTP client, authentication, security source, hooks, API version and native `ErrorThrowable` hierarchy. For a delegated profile, explicitly supply the native `xActiveProfile` header parameter; the server validates ownership, scope and environment. The optional `factuareaVersion` override is also a header and preserves the existing version hook. The client does not configure a second HTTP client or select a tenant from a payload.

```php
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\Model\CrmCreateLeadParameters;
use Factuarea\Sdk\Custom\Crm\Model\CrmCreateLeadRequest;
use Factuarea\Sdk\Custom\FactuareaClient;

$sdk = FactuareaClient::create(getenv('FACTUAREA_API_KEY'));
$crm = new CrmClient($sdk);
$result = $crm->leads->crmCreateLead(new CrmCreateLeadParameters(
    body: new CrmCreateLeadRequest(name: 'Sandbox example'),
    idempotencyKey: $persistedOriginalKey,
));
$id = $result->body->data->id;
```

PHP-valid operation names match their native OpenAPI `operationId`. The five PublicHelpCenter IDs contain punctuation; the inventory records their PHP-safe `phpMethod` while the unchanged native ID is passed to SDK hooks. The complete current method, path, scope and DTO inventory is [crm-operations.json](../spec/crm-operations.json). DTOs follow the API's UUID `id`/`*_id` names, with PHP properties in camel case. JSON object keys and empty object/array shapes are preserved in responses and iterators. Decimal amounts and probabilities stay strings. Dates stay explicit API strings. `Omitted::Value` means absent; `null` clears only a nullable field. Closed DTOs reject authority fields and unknown input without coercion. Local validation covers the structural keywords emitted by this native fragment; current permission, entitlement and business rules remain the server's responsibility.

Persist the original printable ASCII idempotency key with each intent before sending a non-GET operation. The client sends the supplied key unchanged and does not automatically retry non-GET requests. A confirmed mutation can return its full representation or a typed minimal receipt with `confirmed=true` and `representationAvailable=false`; retain that identity. `CrmResponse` exposes the typed body, original key, status and raw response. `replayed()` checks the native `Idempotent-Replayed` header. A transport failure on an effect throws `UnconfirmedMutationException` with the original key; reconcile before starting another intent. Native HTTP errors retain the SDK's error object and raw response, including CAS conflicts, scope denial, all validation fields and `Retry-After`; a server error must not be treated as proof that nothing committed.

Each paginated GET provides an `Items` method, for example:

```php
use Factuarea\Sdk\Custom\Crm\Model\CrmListLeadsParameters;
use Factuarea\Sdk\Custom\Crm\Model\LeadFilters;

foreach ($crm->leads->crmListLeadsItems(
    new CrmListLeadsParameters(limit: 25, filters: (new LeadFilters(status: 'open'))->toJson()),
    maxPages: 100,
) as $lead) {
    // Typed Lead DTO. Scope is revalidated on every server request.
}
```

These methods use the existing `PageIterator`, adapting the native nested Lead/Pipeline cursor envelopes, ContactPeople page metadata and KnowledgeBase numbered search pages (`data.items`, `page`, `per_page`, `total`). Cursors remain opaque. Filters and sorting are retained across pages; the caller's request DTO stays unchanged. An invalid or repeated cursor and an explicit `maxPages` limit raise errors rather than silently truncating results. An `Items` method for a scoring run iterates its result rows, preserving the native run envelope in individual GET responses.

[examples/crm.php](../examples/crm.php) lists sandbox leads by default. Its explicit `create` mode requires a persisted key in `CRM_ORIGINAL_IDEMPOTENCY_KEY`. Keep credentials in the host environment; no key belongs in a browser, a fixture or Git.

The native fragment contains 77 operations (66 paths, 106 schemas). The additional `$crm->knowledgeBase` group has 23 operations: article search/show/versions, ticket suggestions, eight editorial effects with eight explicit original-receipt recoveries, and category list/save/recovery. `$crm->publicHelpCenter` has five administrative operations: read, publish, unpublish and the two original-receipt recoveries. These methods implement authenticated administration; audience and `enabled` values are stored facts and grant no anonymous or portal access. The fragment SHA-256 is `4a2cb151faef526aa8c8fd26c29a1d3f6acd019335e13b058b86671f40eb609f`.

Knowledge article creation sends `expectedVersion: 0` and lets the server assign the identity. A save sends the original positive CAS and complete editorial fields. Category creation omits `id` and supplies `expectedVersion: null`, the original taxonomy CAS and an explicit nullable `parentId`; editing supplies the existing ID and its positive CAS. Center publishing requires literal `confirmed: true`; an omitted or null ID uses CAS zero and an existing ID requires positive CAS. Withdrawal supplies the existing route ID, positive CAS and explicit confirmation.

Every receipt recovery is a GET with the persisted original `Idempotency-Key` header and no new intent body or latest-head lookup. Returned receipts retain the original effect ID, operation, expected CAS and original snapshot. Article and category optional fields remain `Omitted::Value` when currently masked; nullable publication facts remain distinct from omitted facts. Public center administration has a required nullable `center`, and a present center is a fully typed `PublicHelpCenterAdministrativeCenter`. Its publish/unpublish receipts retain the native `allOf` constraint on `enabled`.

```php
use Factuarea\Sdk\Custom\Crm\Model\CrmRecoverKnowledgeArticleSaveReceiptParameters;

$receipt = $crm->knowledgeBase->crmRecoverKnowledgeArticleSaveReceipt(
    new CrmRecoverKnowledgeArticleSaveReceiptParameters(idempotencyKey: $persistedOriginalKey),
);
// Retain this original receipt even if a later article GET has a newer CAS.
$effectId = $receipt->body->data->effectId;
$originalVersion = $receipt->body->data->article->version;
```

`Tests/Custom/Crm/KnowledgeBaseHttpTest.php` covers all 28 additional native routes and their DTOs using an offline configured Guzzle client. Its checks include original-key recovery after a newer head, taxonomy CAS/null rules, masked projections, typed center snapshots, numbered search, native errors and an ambiguous effect followed by explicit recovery. These checks do not execute a deployed endpoint or establish server activation. The previously verified 49-operation suite is retained separately.

The extension is reproducible with `python3 tools/generate-crm.py` followed by `vendor/bin/pint src/Custom/Crm` from [crm-openapi.json](../spec/crm-openapi.json), a separately frozen native fragment. Effect classification comes from [crm-effects.json](../spec/crm-effects.json), checked against the frozen native handler handoff; a fresh source inventory can be exported with `php tools/export-crm-effects.php /path/to/app/backend`; it distinguishes Command/Query handler interfaces rather than inferring effects from scopes or HTTP verbs. It leaves the pinned full public spec, generated Speakeasy core and existing package version intact. `src/Custom/` and `Tests/` survive Speakeasy regeneration; rerun the CRM renderer when its native fragment changes. The fragment supplies source contracts, not proof of public availability: server release/plan/ACL gates remain authoritative.

This delivery does not implement Opportunity or Activity, run a real sandbox workflow, regenerate the full Speakeasy distribution, publish to Packagist, ratify plans or activate CRM in production. The [source evidence](CRM-SOURCE-EVIDENCE.json) lists the executed local checks and those remaining steps separately.
