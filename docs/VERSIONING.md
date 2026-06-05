# Versioning

The Factuarea PHP SDK follows [Semantic Versioning](https://semver.org/) and
pins the API version it was generated against. This document explains the two
version numbers in play and how they map to each other (design D6).

---

## Two versions

| Version                 | What it is                                                                 | Format       |
| ----------------------- | ------------------------------------------------------------------------- | ------------ |
| **SDK version**         | The Composer/Packagist version of `factuarea/factuarea-php`               | SemVer `x.y.z` |
| **`Factuarea-Version`** | The date-based API version this SDK release targets (Stripe-style header) | `YYYY-MM-DD` |

The API is served at a flat `/v1` plus an optional `Factuarea-Version` request
header. **Every SDK release pins one `Factuarea-Version` and sends it on every
request** (`src/Custom/Version/FactuareaVersionHook.php`). This means the API's
behaviour is frozen for an integrator until they upgrade the SDK — upgrading is
explicit and opt-in, never a surprise from a server-side change.

The current default is **`2026-06-04`** — the date of the OpenAPI spec frozen in
P0 (source commit `e822661bc`), exposed as
`FactuareaVersionHook::DEFAULT_VERSION`.

### Overriding the pinned version

A caller can send a different `Factuarea-Version` (e.g. to try a newer API
behaviour without upgrading the SDK). The hook never overwrites an explicit
header, so any per-call or per-client header you set wins.

---

## SemVer rules for the SDK

| Bump      | When                                                                                                                  |
| --------- | ------------------------------------------------------------------------------------------------------------------- |
| **major** | A breaking change to the SDK's public surface (renamed/removed method, changed signature), **or** a bump of the default `Factuarea-Version` that changes observable behaviour. |
| **minor** | New operations / resources / features that are backwards compatible.                                                 |
| **patch** | Bug fixes, doc fixes, dependency bumps with no surface change.                                                       |

`1.0.0` is reserved for the **API's GA event** (no fixed date). Until then the
SDK stays on `0.x`, which signals that the surface may still change.

---

## `Factuarea-Version` ↔ SDK-version mapping

| SDK version | `Factuarea-Version` | Notes                          |
| ----------- | ------------------- | ------------------------------ |
| `0.1.0`     | `2026-06-04`        | Initial pre-GA release.        |

When a new spec is pinned (see [`SPEC_SYNC.md`](SPEC_SYNC.md)):

1. Bump `php.version` in `.speakeasy/gen.yaml`.
2. If the API's `Factuarea-Version` advanced, update
   `FactuareaVersionHook::DEFAULT_VERSION` and decide the SemVer bump by whether
   the new version changes observable behaviour (major) or only adds surface
   (minor).
3. Add a row to this table and a section to [`CHANGELOG.md`](../CHANGELOG.md).
4. Tag `vX.Y.Z`; the [release workflow](../.github/workflows/release.yml) verifies
   the tag matches `gen.yaml` before publishing.
