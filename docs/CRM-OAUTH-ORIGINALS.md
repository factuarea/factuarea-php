Native OAuth CRM and original recovery

Choose the actual native OAuth bearer credential. API keys continue to use their existing factory/configuration. Never put an API key into the OAuth token option or synthesize a token-family identifier.

| Read | Native operation | Fine scope | OAuth grant |
| --- | --- | --- | --- |
| GET /v1/crm/credential-context | crm_access_context.show | crm_access_context:read | crm_access_context.read |
| GET /v1/crm/leads/create/original | crm_leads.create | crm_leads:write | crm_leads.write |
| GET /v1/crm/opportunity/create/original | crm_opportunities.create | crm_opportunities:write | crm_opportunities.write |
| GET /v1/crm/opportunity/{opportunity}/stage/original | crm_opportunities.move_stage | crm_opportunities:write | crm_opportunities.write |
| GET /v1/crm/crmactivity/create/original | crm_activities.create | crm_activities:write | crm_activities.write |

Credential context requires actual OAuth. It returns native company, credential and client UUIDs, issuer environment, fine scopes and authorization_version. It contains no API-key account projection or client-generated family/permission claims.

Each original GET sends only the original Idempotency-Key and original_body_sha256 query. Retain the exact first UTF-8 JSON object body: whitespace, key order, decimal strings, omitted fields and explicit nulls all contribute to SHA256. Never recompute it from parsed JSON. An opportunity-stage lookup also retains its native opportunity UUID.

The original receipt state is confirmed, pending, in_progress, unconfirmed or missing. Confirmed retains the original 2xx status and exact JSON response body as a string. Every other state retains a null original_response. Receipt validation rejects a different body hash, unknown state or fabricated confirmation. A lookup never generates a new key or emits a mutation, including after 401, 429, timeout, cancellation, lost response or OAuth refresh.

Refresh remains an explicit caller/issuer action. Supply the current access token for the next GET; the server proves completed native refresh ancestry and current authority. A distinct API key remains a real singleton credential and receives no invented OAuth family.

HTTP transports normalize whitespace at the edges of header values. These clients reject such Idempotency-Key inputs before dispatch rather than silently trimming them. Printable ASCII keys of 1–255 bytes with internal spaces are preserved.

Source delta only: generated SDK/CLI files and pinned OpenAPI snapshots are unchanged. The frozen input snapshots predate these routes, so the handwritten route table above is the native Source contract for this delta. Package regeneration, publication and deployment remain separate work.
\nPHP usage\n\n\x60\x60\x60php\n$sdk = FactuareaClient::createOAuth(fn () => $currentAccessToken);\n$reads = FactuareaClient::oauthOriginals($sdk);\n$context = $reads->credentialContext();\n$intent = new OriginalIntent('leadCreate', 'first original key', $exactFirstWireBody);\n$receipt = $reads->recover($intent);\n$exactResponseBody = $receipt->originalBody;\n\x60\x60\x60\n\nUse leadCreate, opportunityCreate, opportunityStage and activityCreate. Only opportunityStage accepts a fourth UUID argument. Native OAuth dispatch resolves the current token per HTTP dispatch and blocks a repeated mutating Request instance, even if generated retry options are overridden. Redirects are disabled. The existing API-key factory and retry configuration remain unchanged.\n

Repository recognition (original delivery task1.1)

Actual checkout: /home/nando/Proyectos/factuarea-app-worktrees/dev-2-crm/development/factuarea-php-servicelevel. Own branch: feature/dev2-servicelevel-php-sdk-native-20261009. Baseline: f21efe032175a38481e20c296268148e7c670a6d.

Loaded workspace AGENTS.md/REMOTE-SETUP.md, app AGENTS.md and applicable backend/contract rules before editing. The three SDK/CLI checkouts contain no local AGENTS.md/CLAUDE.md; inherited workspace instructions apply. PHP also follows docs/REGENERATION.md and confines handwritten code to src/Custom. Tooling: PHP >=8.2; Speakeasy2.943.0; handwritten namespace Factuarea\\Sdk\\Custom; Guzzle7; PHPUnit12.5.38 on PHP8.3.35.

Frozen generator input: spec/openapi.json; SHA256 79d667ccae54b0ca7cf19448ab6ffca2a09f13c87a5a8256ba83ce20336bfa87. Existing generated operations and package versions remain compatible. The native handwritten extension adds current OAuth identity and read-only original recovery without repurposing API-key resolution. No generation, installed package release or publication is claimed by this slice. The five added reads are documented by exact native paths and scopes above.
