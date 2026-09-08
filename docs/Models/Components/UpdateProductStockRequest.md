# UpdateProductStockRequest

Public REST API v1 — PUT /v1/products/{uuid}/stock.

Body: `{ stock: numeric-string, operation?: 'set'|'increase'|'decrease' }`.
`operation` defaults to `set` (replace). Accepts also `add` / `subtract`
as aliases for `increase` / `decrease` for ergonomics.


## Fields

| Field                                                                                                           | Type                                                                                                            | Required                                                                                                        | Description                                                                                                     |
| --------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------- |
| `stock`                                                                                                         | *float*                                                                                                         | :heavy_check_mark:                                                                                              | N/A                                                                                                             |
| `operation`                                                                                                     | [?Components\UpdateProductStockRequestOperation](../../Models/Components/UpdateProductStockRequestOperation.md) | :heavy_minus_sign:                                                                                              | N/A                                                                                                             |