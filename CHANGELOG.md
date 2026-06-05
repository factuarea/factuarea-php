# Changelog

All notable changes to the Factuarea PHP SDK are documented here. This project
adheres to [Semantic Versioning](https://semver.org/). The SDK pins the
`Factuarea-Version` it was generated against and sends it on every request.

## [0.1.0] — Unreleased

Initial pre-GA release.

### Added

- Type-safe PHP 8.2+ SDK (`Factuarea\Sdk`) covering the 234 operations of the
  Factuarea public API v1, generated with Speakeasy from the pinned OpenAPI
  document (`spec/openapi.json`, source commit `e822661bc`).
- Bearer API-key authentication; the key prefix (`fact_test_` / `fact_live_`)
  selects the environment.
- `FactuareaClient::create()` ergonomic factory entry point.
- Automatic backoff retries on `429` + `5xx` (honouring `Retry-After`), never on
  other `4xx`.
- Automatic `Idempotency-Key` (UUID v4) on every mutating request, reused across
  retries; overridable per call.
- Cursor auto-pagination via `PageIterator` over the canonical
  `{ data, has_more, next_cursor }` envelope.
- Typed error hierarchy exposing the full error envelope, including `request_id`;
  the API key is never present in any exception message.
- HMAC-SHA256 webhook signature verifier (`WebhookVerifier`) matching the backend
  scheme (`Factuarea-Signature: t=<unix>,v1=<hex>`), with constant-time
  comparison, configurable timestamp tolerance, and rotation grace-window support.

### Notes

- Not yet published to Packagist (install from GitHub until publication).
- The `Factuarea-Version` baseline for this release is the date of the spec frozen
  in P0.
