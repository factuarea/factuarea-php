# Fiscal

Tax deadline details, only in the `fiscal_deadline` layer; `null` otherwise.


## Fields

| Field                                                   | Type                                                    | Required                                                | Description                                             |
| ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- |
| `model`                                                 | *string*                                                | :heavy_check_mark:                                      | Tax form (`303`, `111`…).                               |
| `period`                                                | *string*                                                | :heavy_check_mark:                                      | Period key (`2026-3T`, `2026`…).                        |
| `taskId`                                                | *string*                                                | :heavy_check_mark:                                      | UUID of the task generated for the deadline, or `null`. |