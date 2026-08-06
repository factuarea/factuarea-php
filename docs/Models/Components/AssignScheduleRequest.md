# AssignScheduleRequest


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `employeeId`                                                  | *string*                                                      | :heavy_check_mark:                                            | Employee ID (UUID v7) the schedule is assigned to.            |
| `effectiveFrom`                                               | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Assignment effective start date, in `Y-m-d`.                  |