# PublicApiV1PurchaseOrdersReceiptsListStatus

Goods receipt status. Under a purchase order this is the ONLY filter published: the order is fixed by the path, so asking for it again in the query string is rejected as an unknown parameter. Exact match on `status`.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Created`   | created     |
| `Posted`    | posted      |
| `Cancelled` | cancelled   |