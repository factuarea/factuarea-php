# Private native CRM Source profile

`Factuarea\Sdk\Custom\Crm\Native139\SourceClient::privateContract($sdk)` explicitly selects the unreleased native Source export. The normal `CrmClient`, public spec pins, generated Speakeasy consumers and SDK110 export aliases retain their existing contract.

The exact native export is stored at `spec/crm-native139-source.json`, with its producer hash and OFF profile at `spec/crm-native139-provenance.json`. Generation consumes that literal file; no schema or missing endpoint is supplied by hand. The input139 SHA-256 is `438b12b53be7ae7535cd6c85ba7dea06c76d84a33024db0e0232ac8ba279be63`. This is Source metadata, with `published=false`, `ratified=false`, capabilities empty and delivery disabled. Selecting the PHP namespace does not change backend flags, plans, scopes or grants.

```bash
python3 tools/generate-crm.py --private-source139 spec/crm-native139-provenance.json
```

The mode writes only `Native139`, its private source/provenance, `crm-native139-operations.json` and `crm-native139-schemas.json`. The latter contains the unchanged native schema definitions transitively reached by generated DTOs; its hash is retained beside the literal export hash. Validation loads this smaller derived cache instead of parsing unrelated operations. The normal generator input/output remains separate. Its operations inventory records native paths, methods and declared fine/OAuth scopes; missing OAuth metadata is retained as absent, never inferred from a fine scope.

The native generated ContactPeople, Leads, Pipelines, KnowledgeBase and PublicHelpCenter clients use the existing HTTP/auth/hooks/error transport. Opportunities and Activities expose the exact native create, create Original, Current and list operations at the singular `/crm/opportunity` and `/crm/crmactivity` paths. Their Original reads use the retained write key and exact body hash under writer scopes; Current/list use their separate reader scopes. Creation never retries a POST, and a lost response is recovered with the generated Original GET. An Activity task alias does not establish Task availability or creation.

The private profile resolves configured security immediately before each factual attempt and accepts only the success statuses actually declared by that operation. Owner UUIDs, versions, decimal strings, omitted fields and explicit null remain governed by the native schemas and server authority.

The import recovery consumer takes a caller-retained `ApplyOriginalIntent::restore($run, $bytes, $sha256, $key)`. It validates the retained hash, then reads `GET crm/imports/{run}/apply/original` with the same key and `original_body_sha256` query. Missing, in-progress and unconfirmed results preserve a null original response. Confirmed results retain the exact original202 response string. `imports->current(...)` is a separate owner Query and never upgrades any Original result. The SDK stores no durable receipt or authority.

The six-owner export consumer accepts the existing SDK110 `ExportIntent`; its native request/result models are generated from this private export. The existing resend fence, restored-intent readback and byte/hash/key semantics remain in the SDK110 producer. A repeated known intent reads Original. Only ready allows the existing native CSV stream; decimal bytes are never parsed or converted to floats. Native errors remain visible after current reauthorization.

Documents expose actual current GET/list with native field omission, UUIDv7 and record masks. Agenda exposes the published Query-only `GET agenda`, its native date window, optional sources/assignee and the layers actually served. Served layers neither grant scopes nor establish free/busy or Booking availability.

Input139 does not publish a document Original/restore carrier. Its import Apply POST lacks a native requestBody and a typed202 response, so no replacement POST contract is invented in this profile. ImportOpenApi140 owns that producer correction; its next actual native export can be consumed through the same explicit mode.

Two HTTP-boundary cases are prepared in `Tests/Custom/Crm/Native139/NativeContract139HttpTest.php`: retained import Original versus Current plus lost Opportunity/Activity creation replies recovered through Original GET with current security; and six-owner CSV recovery alongside current document masks, served agenda layers and a later native403. The first preserves omitted versus null and exact decimal strings, and checks one POST followed only by Original reads. They use Guzzle MockHandler and fixture-only `.invalid` URLs. No tests, runtime, API, database, release or installation were executed or certified during this development.
