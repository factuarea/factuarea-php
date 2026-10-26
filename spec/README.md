# OpenAPI spec (pinned)

`openapi.json` is a copy of the Factuarea public OpenAPI document, **pinned** to
commit `0c2bc0e5fc` of the private `factuarea` repository
(`php artisan public-api:export-spec --api=public-api`), copied byte for byte.

- OpenAPI **3.1.0**, **714** operations over **572** paths (633 under
  `/companies/{company}`, 70 under `/accounts/{account}`, 11 at the root).
  SHA-256 `0219a93b32c6a223dd6c75b1b97371fa1ec3272ecca1f2da2c9d50b2ca55bf14`.
- `src/` is still the generation of `0.8.0` (664 operations, 652 generated; the 12
  seat offer redemption operations use the JSON Schema `not` keyword, which the
  generator does not support). The 50 document-template operations this pin adds
  have no typed method until the next regeneration: see
  [`../docs/REGENERATION.md`](../docs/REGENERATION.md).
- Committed here so the SDK is fully reproducible from this repository alone.
- The SDK is generated from this file. To move to a newer API version, replace
  this file with the new pinned document and follow
  [`../docs/REGENERATION.md`](../docs/REGENERATION.md).

Do not hand-edit this file. Generation-time adjustments are expressed as
overlays in [`../.speakeasy/overlays/`](../.speakeasy/overlays/), which keep the
pinned source byte-stable.
