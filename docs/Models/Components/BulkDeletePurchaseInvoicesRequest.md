# BulkDeletePurchaseInvoicesRequest

Public REST API v1 — POST /v1/purchase_invoices/bulk-delete (canónico). El
alias legacy `DELETE /v1/purchase_invoices/bulk` se eliminó en el change
`public-api-official-sdks` (P0 1.3).

Body requerido: `ids` (array de UUIDs entre 1 y 100). La pertenencia al
tenant se valida en el controller — UUIDs desconocidos se reportan como
fallidos.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |