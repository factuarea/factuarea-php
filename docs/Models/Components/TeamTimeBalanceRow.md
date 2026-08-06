# TeamTimeBalanceRow

A single row of the team time balance summary: the monthly totals of one active employee for the Control Horario (time tracking) module.


## Fields

| Field                                         | Type                                          | Required                                      | Description                                   | Example                                       |
| --------------------------------------------- | --------------------------------------------- | --------------------------------------------- | --------------------------------------------- | --------------------------------------------- |
| `employeeId`                                  | *string*                                      | :heavy_check_mark:                            | UUID v7 of the employee.                      | 01933a1f-8c21-7b3d-9e4f-1a2b3c4d5e01          |
| `employeeName`                                | *string*                                      | :heavy_check_mark:                            | Display name of the employee.                 | Lucía Fernández Ruiz                          |
| `totalExpectedMinutes`                        | *int*                                         | :heavy_check_mark:                            | Total expected working minutes of the month.  | 9600                                          |
| `totalWorkedMinutes`                          | *int*                                         | :heavy_check_mark:                            | Total worked minutes of the month.            | 9720                                          |
| `totalBalanceMinutes`                         | *int*                                         | :heavy_check_mark:                            | Month balance in minutes (worked − expected). | 120                                           |
| `totalOvertimeMinutes`                        | *int*                                         | :heavy_check_mark:                            | Total overtime minutes of the month.          | 90                                            |