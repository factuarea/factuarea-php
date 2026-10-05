# ProjectTasksImportTask


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `index`                                                       | *int*                                                         | :heavy_check_mark:                                            | Position of the task in the document.                         |
| `id`                                                          | *string*                                                      | :heavy_check_mark:                                            | UUID of the created task, or `null` when it failed.           |
| `key`                                                         | *string*                                                      | :heavy_check_mark:                                            | Key of the created task (`DEV-12`), or `null` when it failed. |
| `error`                                                       | *string*                                                      | :heavy_check_mark:                                            | Why the task was not created, in Spanish, or `null`.          |