# OpenAPI spec (pinned)

`openapi.json` is a copy of the Factuarea public OpenAPI document, **pinned** to
commit `e639f925ff` of the `feat/epic-nif-axis-api` branch of the private
`factuarea` repository (`php artisan public-api:export-spec --api=public-api`).

- OpenAPI **3.1.0**, **664** operations over **534** paths (652 generated; the 12 seat offer redemption operations use the JSON Schema `not` keyword, which the generator does not support).
- Committed here so the SDK is fully reproducible from this repository alone.
- The SDK is generated from this file. To move to a newer API version, replace
  this file with the new pinned document and follow
  [`../docs/REGENERATION.md`](../docs/REGENERATION.md).

Do not hand-edit this file. Generation-time adjustments are expressed as
overlays in [`../.speakeasy/overlays/`](../.speakeasy/overlays/), which keep the
pinned source byte-stable.
