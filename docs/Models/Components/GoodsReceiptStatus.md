# GoodsReceiptStatus

Lifecycle status: `created` (registered, nothing moved yet), `posted` (accounted for — the stock went in and the ordered quantities moved) and `cancelled` (reverted). Posting and cancelling are both irreversible in the sense that no operation of this API undoes them: a posted receipt is cancelled, and a cancelled one is not reopened.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Created`   | created     |
| `Posted`    | posted      |
| `Cancelled` | cancelled   |