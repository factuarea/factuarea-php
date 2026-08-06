# BulkDeletePurchaseInvoicesRequest

Delete several purchase invoices in one request. `ids` is an array of 1 to 100 UUIDs; unknown identifiers are reported as failed rather than failing the whole request.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |