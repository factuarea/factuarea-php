# ManagedAccountImportPreviewTotals

Rows by status. The five statuses are always present (0 when there is none).


## Fields

| Field              | Type               | Required           | Description        | Example            |
| ------------------ | ------------------ | ------------------ | ------------------ | ------------------ |
| `totalRows`        | *int*              | :heavy_check_mark: | N/A                | 3                  |
| `valid`            | *int*              | :heavy_check_mark: | N/A                | 2                  |
| `warning`          | *int*              | :heavy_check_mark: | N/A                | 0                  |
| `alreadyExists`    | *int*              | :heavy_check_mark: | N/A                | 0                  |
| `blocked`          | *int*              | :heavy_check_mark: | N/A                | 1                  |
| `error`            | *int*              | :heavy_check_mark: | N/A                | 0                  |