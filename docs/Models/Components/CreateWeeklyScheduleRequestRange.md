# CreateWeeklyScheduleRequestRange


## Fields

| Field                                                       | Type                                                        | Required                                                    | Description                                                 |
| ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- |
| `start`                                                     | *string*                                                    | :heavy_check_mark:                                          | Range start time (HH:MM, 00:00-23:59).                      |
| `end`                                                       | *string*                                                    | :heavy_check_mark:                                          | Range end time (HH:MM; 24:00 is allowed as end of workday). |
| `flexible`                                                  | *?bool*                                                     | :heavy_minus_sign:                                          | N/A                                                         |
| `requiredMinutes`                                           | *?int*                                                      | :heavy_minus_sign:                                          | N/A                                                         |