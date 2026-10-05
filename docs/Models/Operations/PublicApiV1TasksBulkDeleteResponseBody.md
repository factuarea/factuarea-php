# PublicApiV1TasksBulkDeleteResponseBody


## Fields

| Field                                                                                           | Type                                                                                            | Required                                                                                        | Description                                                                                     |
| ----------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------- |
| `data`                                                                                          | [Components\TaskBulkResult](../../Models/Components/TaskBulkResult.md)                          | :heavy_check_mark:                                                                              | Result of a bulk task operation. Tasks that no longer exist or were moved are skipped silently. |