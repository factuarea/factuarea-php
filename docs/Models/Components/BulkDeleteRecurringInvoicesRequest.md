# BulkDeleteRecurringInvoicesRequest

Delete several recurring invoices in one request. `ids` is an array of 1 to 100 UUIDs; unknown or cross-tenant identifiers are reported under `failed`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |