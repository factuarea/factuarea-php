# CreateSeriesRequest

Public REST API v1 — POST /v1/series.

Required body: `code` (string, max 10 — invariant of the VO `SeriesCode`),
`document_type` (internal enum whitelist `SeriesType`). Optional: `name`,
`prefix`, `year_reset` (boolean).

`code`, `name` and `year_reset` are honored and materialized by the `Series`
aggregate (enables multi-series with distinct codes per type). `prefix`
is an OUTPUT alias of `code` in the Resource — it is not mapped to the Command.

The `max:10` limit of `code` must match the invariant of
`SeriesCode::create()` (throws if empty or > 10 chars). Without this
alignment, an 11+ char code would pass validation and blow up in the VO
with an HTTP 500 instead of the expected 422.

Public `document_type` whitelist: `invoice, quote, delivery_note,
proforma`. The `contract` type is outside the public v1 surface
(it is not a billable document exposed to integrators) even though the
domain VO `SeriesType` still supports it for the SPA. `purchase_invoice`
and `recurring_invoice` are not yet supported by the internal BC and are
rejected in validation — they will be added additively when the domain supports them.


## Fields

| Field                                                                                                    | Type                                                                                                     | Required                                                                                                 | Description                                                                                              |
| -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `code`                                                                                                   | *string*                                                                                                 | :heavy_check_mark:                                                                                       | N/A                                                                                                      |
| `name`                                                                                                   | *?string*                                                                                                | :heavy_minus_sign:                                                                                       | N/A                                                                                                      |
| `documentType`                                                                                           | [Components\CreateSeriesRequestDocumentType](../../Models/Components/CreateSeriesRequestDocumentType.md) | :heavy_check_mark:                                                                                       | N/A                                                                                                      |
| `prefix`                                                                                                 | *?string*                                                                                                | :heavy_minus_sign:                                                                                       | N/A                                                                                                      |
| `yearReset`                                                                                              | *?bool*                                                                                                  | :heavy_minus_sign:                                                                                       | N/A                                                                                                      |