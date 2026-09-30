# LogTaskTimeV1Request


## Fields

| Field                                                              | Type                                                               | Required                                                           | Description                                                        |
| ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ |
| `startedAt`                                                        | *string*                                                           | :heavy_check_mark:                                                 | Start, ISO 8601 with time zone (e.g. `2026-09-28T09:00:00+02:00`). |
| `endedAt`                                                          | *string*                                                           | :heavy_check_mark:                                                 | End, ISO 8601 with time zone; at most 24 h after the start.        |
| `description`                                                      | *?string*                                                          | :heavy_minus_sign:                                                 | Optional description of the work.                                  |
| `billable`                                                         | *?bool*                                                            | :heavy_minus_sign:                                                 | Whether the time is billable (default `true`).                     |