# BulkDeleteProductsRequest

Public REST API v1 — POST /v1/products/bulk-delete.

Body: `{ ids: string[] }`. Accepts between 1 and 200 IDs (UUID v7). Tenant
membership validation is performed by the Handler (filtered by company_id);
foreign IDs are silently ignored and will appear in `skipped`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |