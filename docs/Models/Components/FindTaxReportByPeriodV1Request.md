# FindTaxReportByPeriodV1Request

Public REST API v1 — POST /v1/tax_reports/find-by-period.

Resolves the MOST RECENT generation of a period (latest-wins). The
FormRequest validates ONLY the parseable shape (`year` integer, `quarter`
integer 1-4, `type` enum) and delegates the period business rules to the
Domain (consistent with the 303/347 generation endpoints):
 - year range (`TaxPeriod::guardYear` via the Query handler) → 422
   `invalid_period.year_out_of_range` (param `year`),
 - cross-field BR-TXR-001 "303 requires quarter" / "347 does not allow quarter"
   (`TaxReportType::assertPeriodMatches()`) → 422
   `invalid_period.quarter_required_for_303` /
   `invalid_period.quarter_not_allowed_for_347`.

We accept `303` / `347` as aliases of the canonical values
`modelo_303` / `modelo_347` (consistent with the paths
`POST /v1/tax_reports/303` and `POST /v1/tax_reports/347`).
`prepareForValidation` normalizes the alias before validating the enum.


## Fields

| Field                                                                                                          | Type                                                                                                           | Required                                                                                                       | Description                                                                                                    |
| -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------- |
| `type`                                                                                                         | [Components\FindTaxReportByPeriodV1RequestType](../../Models/Components/FindTaxReportByPeriodV1RequestType.md) | :heavy_check_mark:                                                                                             | N/A                                                                                                            |
| `year`                                                                                                         | *int*                                                                                                          | :heavy_check_mark:                                                                                             | N/A                                                                                                            |
| `quarter`                                                                                                      | *?int*                                                                                                         | :heavy_minus_sign:                                                                                             | N/A                                                                                                            |