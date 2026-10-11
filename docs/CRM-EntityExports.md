# Native CRM entity exports

The hand-written `CrmClient::entityExports` facade supports the Source107 native owner contract for `organization`, `person`, `lead`, `opportunity`, `activity`, and `service_ticket`. `EntityType` contains exactly those six values. A note is an Activity filter (`type: note`), not a seventh export owner.

This module uses the SDK's HTTP client, security source, hooks, API error hierarchy and configured `/v1` server. It does not modify generated code or the SDK's historical OpenAPI snapshot. Publication and pinning of the current public OpenAPI contract are pending the application's coordinated release; this implementation does not certify compatibility against the older snapshot.

## Confirm and retain one intent

Create the lowercase UUIDv7 and key when a human confirms the actual selection. The caller owns confirmation and durable retention; the SDK does not create an identity, confirm an action, infer a grant or enqueue a replacement attempt.

```php
use Factuarea\Sdk\Custom\Crm\CrmClient;
use Factuarea\Sdk\Custom\Crm\EntityExports\EntityType;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportIntent;
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportUnconfirmedException;

$exports = (new CrmClient($sdk))->entityExports;
$intent = new ExportIntent(
    id: $retainedHumanConfirmedUuidV7,
    entityType: EntityType::Opportunity,
    filters: ['status' => 'open', 'expected_close_from' => '2026-10-01'],
    columns: ['uuid', 'amount', 'currency', 'weighted_value'],
    confirmed: $humanConfirmed, // must be true
    idempotencyKey: $retainedIdempotencyKey,
);

// Durably retain all three before the first POST, using your application's store:
// $intent->originalBody, $intent->originalBodySha256, $intent->idempotencyKey.
try {
    $result = $exports->request($intent);
} catch (ExportUnconfirmedException $failure) {
    // Same identity, hash and key; GET only. Never repeat POST after a lost reply.
    $result = $exports->original($failure->intent);
}
```

The exact request has only `id`, `entity_type`, `filters`, `columns`, and `confirmed`. Columns are a unique ordered list of 1–128 native scalar keys. Empty filters serialize as `{}`. Selection object keys are canonicalized once, then the SDK seals the exact transmitted bytes and their SHA-256. This local hash identifies retained request bytes; it is not the native issuer proof, a public response field or a permission. Hooks cannot replace the sealed method, URI, key or request body. POST has no SDK retry or redirect following.

After an application restart, restore the exact retained bytes and use Original. A restored intent also directs `request()` to GET, so reconstruction cannot silently reissue POST:

```php
$intent = ExportIntent::restore($retainedBody, $retainedBodySha256, $retainedKey);
$result = $exports->original($intent);
$status = $exports->status($intent); // another factual GET, no polling loop
```

The SDK has a process-local identity/hash/key guard to prevent a second POST for a known intent, including across facade instances. It stores no job, credential, response, CSV or access decision and is not a durable receipt ledger. A new operation requires a new explicit human confirmation and UUID. A 404, failed or expired Original does not create a replacement job. Native 4xx errors keep the SDK's typed errors; uncertain transport/server/response outcomes retain the intent in `ExportUnconfirmedException`.

## Native selection and authority

`Selection::COLUMNS` and `Selection::FILTERS` describe the possible native vocabulary per owner. They never authorize a column or filter. The server intersects current Company, actor, sandbox, credential origin, record scope, C7 and field masks, and rejects inaccessible selected fields instead of manufacturing nulls. The v1 fine scope is `crm_imports:export`; OAuth uses `crm_imports.export`. Configuring an SDK does not grant either scope or enable a capability.

| Owner | Native period | Representative columns |
| --- | --- | --- |
| organization / person | `updated_from` / `updated_to`, exact UTC `YYYY-MM-DDTHH:mm:ssZ`, inclusive | `id`, `kind`, `name`, `version`, `updated_at` |
| lead | `created_from` / `created_to`, UTC with optional 1–6 fractional digits, inclusive | `uuid`, `status`, `score`, `estimated_value_amount`, `estimated_value_currency` |
| opportunity | `created_from` / `created_to` or `expected_close_from` / `expected_close_to`, `YYYY-MM-DD`, inclusive | `uuid`, `amount`, `currency`, `weighted_value` |
| activity | `due_from` / `due_to`, exact UTC `YYYY-MM-DDTHH:mm:ssZ`, inclusive | `id`, `type`, `status`, `due_at`, `timezone` |
| service_ticket | `period: {from, until}`, RFC3339 with offset; `[from, until)` | `uuid`, `version`, `title`, `status`, `cycle` |

The complete column and filter lists are in `Selection`; `page`, `cursor`, `limit`, unknown fields and float inputs are rejected. Preserve exact decimal values as strings. Complex `typed_filters` must come from the current native Configuration definition: contact owners accept encoded JSON strings; leads accept an object or encoded JSON; opportunities accept their native object. The SDK does not synthesize definition versions, operators, masks or readable/filterable permissions.

The security source configured through the native SDK builder is resolved immediately before each export I/O attempt, including Original and download. Native server authorization remains authoritative. GET paths send no selection, query or body and retain the original Idempotency-Key; no fictitious hash query/header is added.

## Read status and stream the ready binary

The closed response is `{data: {id, entity_type, status}}`. Only a factual `ready` response additionally contains `filename` and `size_bytes`; other states omit both fields. Native states are `processing`, `ready`, `failed` and `expired`. POST returns 202 only for processing, or 200 for an existing terminal Original; Original returns 200.

```php
use Factuarea\Sdk\Custom\Crm\EntityExports\ExportStatus;

if ($exports->status($intent)->body->data->state() === ExportStatus::Ready) {
    // download() rereads current Original and requires ready before the binary GET.
    $download = $exports->download($intent);
    $stream = $download->stream(); // PSR StreamInterface, same native CSV bytes
    while (! $stream->eof()) {
        fwrite($destination, $stream->read(8192));
    }
}
```

`download()` performs GET `/v1/crm/entity-exports/{id}` followed by GET `/v1/crm/entity-exports/{id}/download`. It requires a native 200 `text/csv` response and returns its stream and raw response without JSON decoding, CSV generation, decimal conversion, buffering the entire file, following redirects or inventing a download URL. The native filename is `crm-{entity_type}.csv`. The server reauthorizes the ready source and masks when serving the binary; a later denial propagates even if an earlier read or download succeeded. Non-ready Original throws `ExportNotReadyException` and cannot reach the binary endpoint.

The API does not publish its private request proof or binary SHA-256 in the job DTO. The SDK verifies the returned original ID and entity type, keeps its local intent hash, and leaves private native proof/binary validation with the owner. No live account, provider request or release capability is implied by these source examples.
