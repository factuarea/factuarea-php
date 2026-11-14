# WorkScheduleStats

Number of weekly work schedules of the authenticated company by status and by clocking mode, and employees with a schedule assigned today. Returned by `GET /v1/companies/{company}/work-schedules/stats`.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `total`                                                       | *int*                                                         | :heavy_check_mark:                                            | All the work schedules, active and archived.                  |
| `active`                                                      | *int*                                                         | :heavy_check_mark:                                            | Active work schedules.                                        |
| `archived`                                                    | *int*                                                         | :heavy_check_mark:                                            | Archived work schedules.                                      |
| `validated`                                                   | *int*                                                         | :heavy_check_mark:                                            | Work schedules in validated mode.                             |
| `realClocking`                                                | *int*                                                         | :heavy_check_mark:                                            | Work schedules in real clocking mode.                         |
| `assignedEmployees`                                           | *int*                                                         | :heavy_check_mark:                                            | Distinct employees with a schedule assignment still in force. |