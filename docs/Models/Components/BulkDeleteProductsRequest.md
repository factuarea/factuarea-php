# BulkDeleteProductsRequest

Delete several products in one request. `ids` is an array of 1 to 200 product UUIDs; identifiers that do not belong to your company are ignored and reported under `skipped`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |