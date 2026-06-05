# GenerateModelo303V1Request

Public REST API v1 — POST /v1/tax_reports/303.

F-078: `format` now defaults to `pdf` when omitted. Previously it was
required without being documented and returned 422, confusing integrators.
Allowed values: `txt_aeat`, `pdf`, `excel`. Only `txt_aeat` is
directly submittable on the AEAT portal.

The FormRequest validates ONLY the parseable shape of `year` / `quarter`
(integers) and delegates the period business rules to the Domain:
 - year range (`TaxPeriod::guardYear`) → 422 `invalid_period.year_out_of_range`,
 - quarter range (`TaxPeriod::guardQuarter`) → 422 `invalid_period.quarter_out_of_range`,
 - cross-field BR-TXR-001 "303 requires quarter"
   (`TaxReportType::assertPeriodMatches`) → 422
   `invalid_period.quarter_required_for_303`.

`format` IS validated here with `in:` because `ReportFormat::from()` throws
a native `\ValueError` (not `PublicApiMappable`) on a non-enumerable value
like `docx`: intercepting it in the FormRequest guarantees a 422
`invalid_request_error` (param `format`) instead of a 500.


## Fields

| Field                                                                                                      | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `year`                                                                                                     | *int*                                                                                                      | :heavy_check_mark:                                                                                         | N/A                                                                                                        |
| `quarter`                                                                                                  | *?int*                                                                                                     | :heavy_minus_sign:                                                                                         | N/A                                                                                                        |
| `format`                                                                                                   | [Components\GenerateModelo303V1RequestFormat](../../Models/Components/GenerateModelo303V1RequestFormat.md) | :heavy_check_mark:                                                                                         | N/A                                                                                                        |