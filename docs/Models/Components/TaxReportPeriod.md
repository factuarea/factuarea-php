# TaxReportPeriod

Fiscal period covered by the declaration (nested form).


## Fields

| Field                                                      | Type                                                       | Required                                                   | Description                                                |
| ---------------------------------------------------------- | ---------------------------------------------------------- | ---------------------------------------------------------- | ---------------------------------------------------------- |
| `year`                                                     | *int*                                                      | :heavy_check_mark:                                         | Fiscal year.                                               |
| `quarter`                                                  | *int*                                                      | :heavy_check_mark:                                         | Trimestre (1-4) o `null` para declaraciones anuales (347). |
| `label`                                                    | *string*                                                   | :heavy_check_mark:                                         | Human-readable period label (e.g. `2026 Q1` or `2026`).    |