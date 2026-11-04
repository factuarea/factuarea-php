# CreatePdfTemplatePreviewRequestUnion

Requires the existing `pdfs:read` and additional `pdf_templates:read` scopes. Send exactly one of `example`, `unsaved_form`, `persisted_draft`, `presentation_revision` or `invoice_substitution`, with required purpose, format and locale. A `persisted_draft` source accepts a draft document or a quote, pro-forma or delivery note that has left draft and has no retained presentation yet; with a retained presentation (or an issued invoice) it returns 403. `invoice_substitution` is only valid for `invoice` / `complete` / `a4` and must omit the visual triple. Example and unsaved form require the complete `base_preset`, `layout_version` and `configuration`; owner-bound sources accept that complete triple or omit all three. Partial or explicit-null triples return 422 with field paths. Omission resolves the exact effective root from the authoritative owner on the server, preserves null defaults and retained pins, and performs no import or live fill. A supplied full root must match that root exactly. The commercial identity selector exists only on `example`: omit it or send null/`inherit` for inheritance; `exact_revision` requires both retained identity and revision UUIDs; `draft` requires a library administrator and all six nullable keys (`commercial_name`, `logo_asset_id`, `brand_image_asset_id`, `brand_mode`, `contact_email`, `contact_phone`); `logo_dark_asset_id`, `brand_image_dark_asset_id`, `dark_background_fallback` (default `silhouette`) and `brand_size` (default `medium`) are optional. Draft assets must already be retained; preview neither reads live brand data nor saves, publishes, or asserts continuity. A revision source binds an authentic owner document and exact retained parent, nullable target template (null inherits), captured/current_legal policy and positive `expected_version` CAS. Its returned `source_token` and revision precondition authorize only a later explicit Revise action; they never authorize issue, send, accept or sign. Semantic normalization precedes admission and equivalent previews may resolve to one retained artifact; HTTP idempotency still compares the raw body, so changing the body with the same key returns 409. Missing canonical tenant initialization or disabled revisioned presentation returns a recoverable 422 `pdf_presentation_not_ready` (`invalid_request_error`) with no artifact, intent or reservation: preserve form data and complete authorized initialization. GET and preview never import or initialize; an owner CLI may be an optional initialization entry point, never a required client step. Exact retained prepared/READY reads do not require fresh initialization.


## Supported Types

### `Components\CreatePdfTemplatePreviewRequest1`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequest1
*/
Components\CreatePdfTemplatePreviewRequest1 $value = /* values here */
```

### `Components\CreatePdfTemplatePreviewRequest12`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequest12
*/
Components\CreatePdfTemplatePreviewRequest12 $value = /* values here */
```

### `Components\CreatePdfTemplatePreviewRequest13`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequest13
*/
Components\CreatePdfTemplatePreviewRequest13 $value = /* values here */
```

### `Components\CreatePdfTemplatePreviewRequest2`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequest2
*/
Components\CreatePdfTemplatePreviewRequest2 $value = /* values here */
```

### `Components\CreatePdfTemplatePreviewRequest14`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequest14
*/
Components\CreatePdfTemplatePreviewRequest14 $value = /* values here */
```

### `Components\CreatePdfTemplatePreviewRequest2`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequest2
*/
Components\CreatePdfTemplatePreviewRequest2 $value = /* values here */
```

### `Components\CreatePdfTemplatePreviewRequestA4`

```php
/**
* @var \Factuarea\Sdk\Models\Components\CreatePdfTemplatePreviewRequestA4
*/
Components\CreatePdfTemplatePreviewRequestA4 $value = /* values here */
```

