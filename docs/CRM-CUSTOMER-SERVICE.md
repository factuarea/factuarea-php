# Current native CustomerService contracts

This handwritten subset uses `CustomerService::fromSdk($sdk)` and the configured
Factuarea HTTP client, credentials, version hook and effective profile. It lives
under `src/Custom/` and changes neither the generated SDK nor the pinned OpenAPI,
package version or regeneration lock. PHP 8.2+ is required by this repository.

The source contract is the native `ServiceTicketRequestContext::rulesFor`,
`ServiceTicketResource::serialize`, `ServiceTicketOperationTransport` and the
Root wiring proposal for the three producer operations. Root owns registration
in routes, capabilities and scopes. This SDK diff and HTTP mock tests do not
prove route installation, production availability, sandbox producer behavior or
current ACL integration. The original CRM SDK delivery remains partial.

| Method / native operation ID | v1 method and path | Closed JSON body / query |
| --- | --- | --- |
| `moveServiceTicketToStage` | POST `/crm/service-tickets/{ticket}/move-to-stage` | `expected_version`, `pipeline_id`, `stage_id` |
| `attachServiceTicketDocument` | POST `/crm/service-tickets/{ticket}/attachments` | `expected_version`, `document_id`, `document_version` |
| `detachServiceTicketDocument` | POST `/crm/service-tickets/{ticket}/attachments/remove` | `expected_version`, `attachment_id` |
| `getServiceTicket` | GET `/crm/service-tickets/{ticket}` | No body or query |
| `listServiceTickets` | GET `/crm/service-tickets` | `page`, `per_page`, optional `status`, `sort`, `direction` |
| `getServiceTicketTimeline` | GET `/crm/service-tickets/{ticket}/timeline` | `page`, `per_page` |

Paths are relative to the SDK's `/v1` base. Native writes require fine scope
`customer_service:write` / OAuth `customer_service.write`; reads require their
read equivalents. Company, actor, sandbox, claim/effect identity and authorization
are resolved by the backend. The API key chooses the realm; no body flag selects
company or sandbox. CRM-168 stage mapping is distinct from the CRM-172 workspace
move with WIP/approval. This request accepts no status, approval or terminal time.
No stage list, multipart upload, public private-receipt endpoint or extra
CustomerService operation is introduced.

All request identities are UUIDv7 and serialize to public `*_id` keys. Ticket and
document CAS are JSON integers >=1. Actor identities retain their native UUID
version and spelling on reads. Public enum values are preserved as strings;
request filters accept only the native closed set. Responses retain omitted
fields separately from explicit null. `Ticket::attachments()` returns null for
an omitted field and an empty array only for an authorized empty list. Attachment
metadata stays omitted when masked; private URLs/paths and raw producer references
are rejected. Current ticket/timeline reads supply the server-owned attachment
ID for detach. A document version is pinned explicitly when attaching; no latest
version or MIME/size is guessed from a local file.

All three writes return HTTP200 with `data: {id, version}`. This is the public
projection of the original private owner receipt, with no attachment/effect ID.
Attach/detach confirm the original ticket CAS+1. A move to an unchanged current
stage can return its original CAS; a changed move returns CAS+1. A newer ticket
head never substitutes for either receipt. Stage/status and document visibility
continue to depend on native owner authorization.

Mutations are sent once, with redirects disabled, even when the generated SDK
retry policy enables backoff. `TicketWriteUnconfirmed` retains an immutable
intent after a timeout, 5xx, post-dispatch 403/404, unconfirmed/revoked response,
unexpected success status or malformed/foreign receipt. Call `recover($intent)`
explicitly using the same operation, body, CAS and `Idempotency-Key`. Recovery
calls the original POST; the backend selects its private original receipt and
never redispatches a reserved ambiguous intent. Reads may inspect a newer head
but cannot replace this saved CAS or certify the earlier write. Native 409 CAS /
key reuse, 422 and 429 errors use the existing SDK typed error with `requestId`.
Neither success/error hooks nor JSON `confirmed` flags can manufacture success.

Recovery binds the original server and credential context and freezes the
original effective profile, API version and hook-supplied request credentials.
No copied credential is stored in the intent. Keep the same configured client
until recovery is resolved. Retain the intent/key in the caller's durable job
state; the SDK provides no secret-bearing serialization or new dispatch policy.

`iterateServiceTickets` uses the real offset pages, preserves filters and stops
at the server count/empty page with an explicit maximum. It does not claim a
cross-page snapshot when records or authorization change. Timeline snapshots
retain the original parent/version and current masks.

See [the composable recipe](../examples/CrmCustomerService.php) for attach,
read-owned attachment identity, detach and same-key recovery. The existing
library client can fetch the authorized document revision before attaching it;
JSON library upload and SPA multipart transport have separate contracts.

The directed `Tests/Custom/Crm/CustomerService/CustomerServiceContractTest.php`
fixtures cover the six native operations, operation-specific receipt CAS,
original receipt after a newer head, ambiguity/recovery, masks, historical
snapshots, native UUIDs, pagination, scope errors and credential separation.
They use Guzzle mocks and send no network request or database operation.

Original delivery mapping: CRM-083 tasks 1.1/1.3 native adaptation; 2.2 current
method subset; 2.3 types/omitted-null; 2.4 typed errors/unconfirmed; 2.5 directed
wire fixtures; 2.7 compiled composable recipe subset. CRM-144 task 2.8 has exact
native contract/capability limits. Generated full CRM inventory, published-spec
sync 2.1, the full Lead→Opportunity→Activity journey, clean installation,
sandbox A/B producer/ACL certification and release gates remain pending. No
BottleCRM code was copied; this is native integration for CRM-249/252/253.
