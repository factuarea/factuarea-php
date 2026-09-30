# UpdateTaskTimeEntryV1Request


## Fields

| Field                                                       | Type                                                        | Required                                                    | Description                                                 |
| ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- |
| `startedAt`                                                 | *?string*                                                   | :heavy_minus_sign:                                          | New start, ISO 8601 with time zone. `null` is not accepted. |
| `endedAt`                                                   | *?string*                                                   | :heavy_minus_sign:                                          | New end, ISO 8601 with time zone; `null` removes the end.   |
| `description`                                               | *?string*                                                   | :heavy_minus_sign:                                          | Description; `null` clears it.                              |
| `billable`                                                  | *?bool*                                                     | :heavy_minus_sign:                                          | Whether the time is billable. `null` is not accepted.       |