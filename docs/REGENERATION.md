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

`spec/openapi.json` is a copy of the Factuarea public OpenAPI document taken from
`backend/public/docs/openapi-public.json` of the private `factuarea` repository
(OpenAPI 3.1). It is committed so the SDK is fully reproducible from this repo
alone.

To update the SDK to a newer API version, replace `spec/openapi.json` with the new
pinned document and regenerate (below). Bump the version in
`.speakeasy/gen.yaml` (`php.version`) and update `CHANGELOG.md`.

### Pin history

| Date | Operations / paths | Source |
| --- | --- | --- |
| 2026-06-05 | 234 | commit `e822661bc` of the private repo |
| 2026-09-09 | 470 / 386 | spec published with Factuarea v1.15.26 |
| 2026-09-18 | 654 / 532 | **frozen contract of the ERP wave** (see below) |

### 2026-09-18 — pinned from a frozen contract, not from the published document

The ERP and omnichannel surface (sales orders, purchase orders, goods receipts,
warehouses, stock transfers, reservations, availability, carriers, returns,
storefront and storefront credentials, plus contacts and the fulfilment
extensions of delivery notes) was regenerated **before it was deployed**. The
source was therefore not the published document but a single artefact exported
once from the backend and frozen for every client repository at the same instant:

- artefact: `backend/public/docs/openapi-public.json` of the private repo,
- `sha256 687f70a156a90cd41c453e87cb6627b3cb3ff85c21db0ff81b392c7b1945f49c`,
- 654 operations over 532 paths, 54 tags (previous pin: 470 over 386, 42 tags),
- delta **+184 operations, −0**: 144 on the eleven new ERP tags, 22 on tags that
  already existed (mostly delivery-note fulfilment) and 18 on `Contacts`.

Regenerating against the **published** document during this window would have
been wrong, not merely stale: `https://docs.factuarea.com/api/openapi` and
`https://api.factuarea.com/v1/openapi.json` still serve the pre-ERP contract, so
a regeneration from either one REMOVES the 184 operations. Verify the pin before
regenerating:

```bash
shasum -a 256 spec/openapi.json
jq '[.paths[] | to_entries[] | select(.key | IN("get","post","put","patch","delete"))] | length' spec/openapi.json
```

For the same reason the **`schedule` trigger of `.github/workflows/spec-sync.yml`
was paused on 2026-09-18**: an unattended run would have read the regenerated SDK
as drift against the older published document and opened a pull request removing
the new operations. The header of that workflow carries the pause note and its
reactivation condition, which is measurable by anyone: the document published at
`$SPEC_URL` declares the **same** number of operations as the copy pinned here
(654 on both sides once the contract is deployed). `repository_dispatch` and
`workflow_dispatch` were left untouched, so the sync still runs when the backend
republishes or when a maintainer asks for it.

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

`.speakeasy/overlays/product-options-namespace.yaml` maps the product-options
operations to a `productOptions` leaf for PHP generation. Speakeasy flattens
nested PHP groups into root classes, and it disambiguates a collision between two
sibling groups by prefixing the parent (`storefront.products.variants` correctly
becomes `StorefrontVariants`) — but it does **not** disambiguate against the
imports it writes into the class itself. Every generated resource class carries
`use Factuarea\Sdk\Utils\Options;`, so a group whose last segment is `options`
generates `class Options` in `Factuarea\Sdk` inside a file that already imports
that short name, and PHP refuses to parse it:

```
Fatal error: Cannot declare class Factuarea\Sdk\Options because the name is
already in use in src/Options.php on line 19
```

Two operations hit this, and the overlay re-groups both:

| Path | Group after the overlay | Entry point |
| --- | --- | --- |
| `/products/{product}/options` | `products.productOptions` | `$sdk->products->productOptions` |
| `/companies/{company}/storefront/products/{product}/options` | `storefront.products.productOptions` | `$sdk->storefront->products->productOptions` |

The storefront one arrived with the 2026-09-18 ERP contract and reproduced the
same fatal on speakeasy 1.796.4, which is why the overlay grew a second action
that day. The HTTP paths, operation IDs, tags and pinned public spec are
unchanged. Keep this overlay on every spec-sync regeneration; remove it only once
a regeneration without it leaves `php -l src/Options.php` at exit 0.

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
- **`.gitattributes` is overwritten** by every `speakeasy run` (measured on
  1.796.4, 2026-09-18): the generator rewrites it down to its single
  `*.php linguist-generated=false` line and drops the hand-maintained
  `export-ignore` block that keeps the Composer dist archive lean. Restore that
  block after each regeneration, before committing — `git diff .gitattributes`
  makes it obvious.
- **Rebuild the autoloader after regenerating.** `composer.json` sets
  `classmap-authoritative: true`, so the autoloader never falls back to PSR-4:
  until the classmap is rebuilt, every class the regeneration added is invisible
  and the test suite dies with `ReflectionException: Class "…" does not exist`.
  `composer dump-autoload --classmap-authoritative` is enough when no dependency
  changed; `composer update` (the step above) also does it.

## Checking the regeneration

`Tests/Contract/PublicMethodSurfaceTest.php` compares the method surface the SDK
publishes with the operations the pinned `spec/openapi.json` declares, **in both
directions**, and fails naming the surplus and the missing ones. A one-way check
passes happily while the generator silently drops a whole family, which is why
this is the first thing to run after a regeneration:

```bash
./vendor/bin/phpunit Tests/Contract/PublicMethodSurfaceTest.php
```

The comparison is by method name rather than by accessor path on purpose: the
overlays may re-group an operation without touching the pinned contract, so the
accessor path legitimately differs from the group the contract declares, while
the method name derives from `operationId`, which no overlay rewrites.

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
