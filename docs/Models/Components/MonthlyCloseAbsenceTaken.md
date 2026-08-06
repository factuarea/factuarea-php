# MonthlyCloseAbsenceTaken

One line of the taken-absence breakdown of an employee in the monthly close report: the absence type and the days taken of that type in the closed period.


## Fields

| Field                                                                  | Type                                                                   | Required                                                               | Description                                                            | Example                                                                |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `absenceTypeName`                                                      | *string*                                                               | :heavy_check_mark:                                                     | Name of the absence type (e.g. “Vacaciones”).                          | Vacaciones                                                             |
| `daysTaken`                                                            | *string*                                                               | :heavy_check_mark:                                                     | Days taken of that absence type in the closed period (decimal string). | 2.00                                                                   |