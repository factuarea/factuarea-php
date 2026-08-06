# BulkDeleteClientsRequest

Delete several clients in one request. `ids` is an array of 1 to 200 UUIDs; identifiers that do not belong to your company are reported under `failed` rather than failing the whole request.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |