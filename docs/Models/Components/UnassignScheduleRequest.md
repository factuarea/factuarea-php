# UnassignScheduleRequest


## Fields

| Field                                                                  | Type                                                                   | Required                                                               | Description                                                            |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `employeeId`                                                           | *string*                                                               | :heavy_check_mark:                                                     | Employee ID (UUID v7) whose assignment is removed.                     |
| `effectiveTo`                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php)          | :heavy_minus_sign:                                                     | Assignment effective end date (Y-m-d); defaults to today when omitted. |