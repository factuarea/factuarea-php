# Support & compatibility

This document is the source of truth for which runtimes the Factuarea PHP SDK
supports and how deprecations and breaking changes are handled.

---

## Runtime support matrix

| PHP version | Status                              |
| ----------- | ----------------------------------- |
| **8.2**     | ✅ Supported — minimum, CI-tested   |
| **8.3**     | ✅ Supported — CI-tested            |
| **8.4**     | ✅ Supported — CI-tested            |
| ≤ 8.1       | ❌ Not supported (EOL / below floor) |

The minimum is declared in [`composer.json`](composer.json) (`"php": ">=8.2"`).
Every push and pull request runs the full test suite against PHP 8.2, 8.3, and
8.4 in [CI](.github/workflows/ci.yml); a release tag re-runs the same matrix
before publishing.

### Dependencies

The SDK is built on a `fetch`-equivalent HTTP layer (Guzzle 7) and is intended
for **server-side** use. Never embed a secret API key in a browser or mobile
client — the key would be exposed.

---

## Stability and the `0.x` line

The SDK is **pre-GA** and on the `0.x` line. Per
[SemVer](https://semver.org/#spec-item-4), `0.x` means the public surface may
still change. We do treat surface changes seriously and document every one, but
do not consider the surface frozen until **`1.0.0`**, which is tied to the
Factuarea API's GA event. See [`docs/VERSIONING.md`](docs/VERSIONING.md).

---

## Deprecation & breaking-change policy

Aligned with the API's date-based versioning (`Factuarea-Version`):

- **Additive changes** (new operations, new optional parameters, new fields) are
  **minor** releases and never break existing call sites.
- **Deprecations**: a method or option scheduled for removal is marked
  `@deprecated` in the source and noted in [`CHANGELOG.md`](CHANGELOG.md) with the
  replacement and the earliest version in which it may be removed. During `0.x`
  the deprecation window is at least one minor release before removal.
- **Breaking changes** (renamed/removed methods, changed signatures, or a default
  `Factuarea-Version` bump that changes observable behaviour) are **major**
  releases (`1.0.0+`). Each is listed in the changelog with a migration note.
- **API version pinning**: because the SDK pins `Factuarea-Version` and sends it
  on every request, a server-side API change never silently alters your
  integration — you adopt it by upgrading the SDK.

---

## Getting help

- **Bugs / feature requests**: open an issue on
  [github.com/factuarea/factuarea-php](https://github.com/factuarea/factuarea-php/issues).
- **API questions**: see the developer docs at https://docs.factuarea.com.
- **Security reports**: email **info@factuarea.com** — do not open a public issue
  for a vulnerability.

Always include the `request_id` from the typed error (`$error->requestId`) when
reporting an API problem; it lets us trace the exact request.
