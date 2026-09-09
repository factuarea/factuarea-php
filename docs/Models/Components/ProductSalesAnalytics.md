# ProductSalesAnalytics


## Fields

| Field                                                             | Type                                                              | Required                                                          | Description                                                       |
| ----------------------------------------------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------- |
| `unitsSold`                                                       | *string*                                                          | :heavy_check_mark:                                                | Exact quantity sold, represented as a decimal string.             |
| `totalRevenue`                                                    | *float*                                                           | :heavy_check_mark:                                                | Sales revenue in EUR.                                             |
| `invoicesCount`                                                   | *int*                                                             | :heavy_check_mark:                                                | N/A                                                               |
| `monthOverMonthDeltaPercent`                                      | *int*                                                             | :heavy_check_mark:                                                | N/A                                                               |
| `trend`                                                           | array<*string*>                                                   | :heavy_check_mark:                                                | N/A                                                               |
| `trendLabels`                                                     | array<*string*>                                                   | :heavy_check_mark:                                                | N/A                                                               |
| `lastBuyer`                                                       | [Components\LastBuyer](../../Models/Components/LastBuyer.md)      | :heavy_check_mark:                                                | N/A                                                               |
| `activity`                                                        | array<[Components\Activity](../../Models/Components/Activity.md)> | :heavy_check_mark:                                                | N/A                                                               |