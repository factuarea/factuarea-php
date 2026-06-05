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
runtime (webhook verifier, idempotency hook, page iterator, client factory) lives
entirely in `src/Custom/` for this reason.

---

## The pinned spec

`spec/openapi.json` is a copy of the Factuarea public OpenAPI document, pinned to
commit **`e822661bc`** of the private `factuarea` repository
(`backend/public/docs/openapi-public.json`, OpenAPI 3.1, 234 operations). It is
committed so the SDK is fully reproducible from this repo alone.

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
   - PSR-7 `RequestInterface` / `ResponseInterface` (idempotency hook, page iterator),
   - PHP's `hash_hmac` / `hash_equals` (webhook verifier),
   so most of the runtime survives a generator change untouched. Only
   `src/Custom/FactuareaClient.php` (which references the generated builder and
   `Security`) needs adjusting.

4. Run `composer test` and `composer stan` before publishing.

Because the spec carries vendor-neutral metadata (`x-speakeasy-*` are ignored by
other generators; pagination/retry semantics are also implemented in
`src/Custom/`), the SDK remains regenerable by any OpenAPI generator.
