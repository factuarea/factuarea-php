# GenerateModelo347V1Request

Public REST API v1 — POST /v1/tax_reports/347.

F-078: `format` now defaults to `pdf` when omitted.
Allowed values: `txt_aeat`, `pdf`, `excel`.

The FormRequest validates ONLY the parseable shape of `year` / `quarter`
(integers) and delegates the period business rules to the Domain:
 - year range (`TaxPeriod::guardYear`) → 422 `invalid_period.year_out_of_range`,
 - cross-field BR-TXR-001 "347 does not allow quarter"
   (`TaxReportType::assertPeriodMatches`) → 422
   `invalid_period.quarter_not_allowed_for_347`.

We accept `quarter` as a nullable integer so that, if the integrator
sends it by mistake, the Domain emits the canonical code instead of
silently ignoring it. `format` IS validated here with `in:` (as in /303)
so that a non-enumerable value like `docx` yields 422 and not a 500 from
the `\ValueError` of `ReportFormat::from()`.


## Fields

| Field                                                                                                      | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `year`                                                                                                     | *int*                                                                                                      | :heavy_check_mark:                                                                                         | N/A                                                                                                        |
| `quarter`                                                                                                  | *?int*                                                                                                     | :heavy_minus_sign:                                                                                         | N/A                                                                                                        |
| `format`                                                                                                   | [Components\GenerateModelo347V1RequestFormat](../../Models/Components/GenerateModelo347V1RequestFormat.md) | :heavy_check_mark:                                                                                         | N/A                                                                                                        |