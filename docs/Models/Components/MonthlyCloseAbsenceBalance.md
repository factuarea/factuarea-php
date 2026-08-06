# MonthlyCloseAbsenceBalance

One available absence balance by type of an employee, frozen at closing time in the monthly close report.


## Fields

| Field                                                                    | Type                                                                     | Required                                                                 | Description                                                              | Example                                                                  |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `absenceTypeName`                                                        | *string*                                                                 | :heavy_check_mark:                                                       | Name of the absence type (e.g. “Vacaciones”).                            | Vacaciones                                                               |
| `availableDays`                                                          | *string*                                                                 | :heavy_check_mark:                                                       | Available days of that absence type, frozen at closing (decimal string). | 18.50                                                                    |