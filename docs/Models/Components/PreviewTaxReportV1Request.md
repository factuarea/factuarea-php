# PreviewTaxReportV1Request

Public REST API v1 — POST /v1/tax_reports/preview.

Computes the report breakdown without persisting any generation or file.
Useful for showing the user what they are about to declare before confirming.

We accept `303` / `347` as aliases of the canonical values
`modelo_303` / `modelo_347`, keeping consistency with the paths
`POST /v1/tax_reports/303` and `POST /v1/tax_reports/347`. The handler
normalizes the value before instantiating the VO `TaxReportType`.


## Fields

| Field                                                                                                | Type                                                                                                 | Required                                                                                             | Description                                                                                          |
| ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| `type`                                                                                               | [Components\PreviewTaxReportV1RequestType](../../Models/Components/PreviewTaxReportV1RequestType.md) | :heavy_check_mark:                                                                                   | N/A                                                                                                  |
| `year`                                                                                               | *int*                                                                                                | :heavy_check_mark:                                                                                   | N/A                                                                                                  |
| `quarter`                                                                                            | *?int*                                                                                               | :heavy_minus_sign:                                                                                   | N/A                                                                                                  |