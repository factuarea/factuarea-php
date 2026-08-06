# EmployeeScheduleWeekPattern


## Fields

| Field                                                                                       | Type                                                                                        | Required                                                                                    | Description                                                                                 |
| ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------- |
| `day`                                                                                       | *int*                                                                                       | :heavy_check_mark:                                                                          | ISO 8601 weekday (1 = Monday … 7 = Sunday).                                                 |
| `ranges`                                                                                    | array<[Components\EmployeeScheduleRange](../../Models/Components/EmployeeScheduleRange.md)> | :heavy_check_mark:                                                                          | Ordered, non-overlapping time ranges worked that day (empty = rest day).                    |