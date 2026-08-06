# TaxReportPreviewPeriod

Previewed fiscal period.


## Fields

| Field                                              | Type                                               | Required                                           | Description                                        |
| -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- |
| `year`                                             | *int*                                              | :heavy_check_mark:                                 | Fiscal year.                                       |
| `quarter`                                          | *int*                                              | :heavy_check_mark:                                 | Quarter (1-4), or `null` for annual reports (347). |
| `label`                                            | *string*                                           | :heavy_check_mark:                                 | Human-readable period label (e.g. `2026 Q1`).      |