# Carryover

How much unused balance carries over at year-end under this policy.


## Fields

| Field                                                                               | Type                                                                                | Required                                                                            | Description                                                                         |
| ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------- |
| `type`                                                                              | [Components\AbsencePolicyType](../../Models/Components/AbsencePolicyType.md)        | :heavy_check_mark:                                                                  | Carryover mode: `none` (no carryover), `capped` (up to `max_days`) or `unlimited`.  |
| `maxDays`                                                                           | *int*                                                                               | :heavy_check_mark:                                                                  | Maximum days that carry over when `type` is `capped`; `null` otherwise.             |
| `expiryMonth`                                                                       | *int*                                                                               | :heavy_check_mark:                                                                  | Month (1..12) on which carried-over days expire, or `null` when they do not expire. |
| `expiryDay`                                                                         | *int*                                                                               | :heavy_check_mark:                                                                  | Day of month on which carried-over days expire, or `null` when they do not expire.  |