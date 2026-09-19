# BulkStatusGoodsReceiptsRequestNewStatus

Target status: `posted` (accounting for the goods, which consumes ordered quantity) or `cancelled` (undoing a receipt, which gives that quantity back). Both are irreversible. `created` is the status a receipt is born in and is never a target.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Posted`    | posted      |
| `Cancelled` | cancelled   |