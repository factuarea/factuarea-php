# EmployeeStats

Number of employees of the authenticated company by status and by type of working day. Returned by `GET /v1/companies/{company}/employees/stats`.


## Fields

| Field                                   | Type                                    | Required                                | Description                             |
| --------------------------------------- | --------------------------------------- | --------------------------------------- | --------------------------------------- |
| `total`                                 | *int*                                   | :heavy_check_mark:                      | All the employees, active and inactive. |
| `active`                                | *int*                                   | :heavy_check_mark:                      | Active employees.                       |
| `inactive`                              | *int*                                   | :heavy_check_mark:                      | Deactivated employees.                  |
| `fullTime`                              | *int*                                   | :heavy_check_mark:                      | Employees with a full-time working day. |
| `partTime`                              | *int*                                   | :heavy_check_mark:                      | Employees with a part-time working day. |