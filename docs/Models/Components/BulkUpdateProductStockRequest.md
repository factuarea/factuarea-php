# BulkUpdateProductStockRequest

Public REST API v1 — POST /v1/products/bulk-update-stock.

Body: `{ updates: [{ product_id: string, stock: int, operation?: 'set'|'add'|'subtract' }] }`.
Accepts up to 500 updates in a single operation.

We accept `items` as an alias of the canonical `updates` field for
forgiveness with integrators following the most common convention. The
controller normalizes it to `updates`.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `updates`                                                     | array<[Components\Update](../../Models/Components/Update.md)> | :heavy_check_mark:                                            | N/A                                                           |