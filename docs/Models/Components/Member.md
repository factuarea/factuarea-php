# Member


## Fields

| Field                                                           | Type                                                            | Required                                                        | Description                                                     |
| --------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- |
| `employeeId`                                                    | *string*                                                        | :heavy_check_mark:                                              | UUID v7 of the employee.                                        |
| `fullName`                                                      | *string*                                                        | :heavy_check_mark:                                              | Display name of the employee.                                   |
| `absences`                                                      | array<[Components\Absence](../../Models/Components/Absence.md)> | :heavy_check_mark:                                              | Approved absences of the employee overlapping the month.        |