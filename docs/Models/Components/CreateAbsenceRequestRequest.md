# CreateAbsenceRequestRequest


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `employeeId`                                                  | *string*                                                      | :heavy_check_mark:                                            | Employee ID (UUID v7) requesting the absence.                 |
| `absenceTypeId`                                               | *string*                                                      | :heavy_check_mark:                                            | Requested absence type ID (UUID v7).                          |
| `startDate`                                                   | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Absence start date, in `Y-m-d`.                               |
| `endDate`                                                     | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Absence end date, in `Y-m-d`, equal to or after the start.    |
| `note`                                                        | *?string*                                                     | :heavy_minus_sign:                                            | Optional note for the request.                                |