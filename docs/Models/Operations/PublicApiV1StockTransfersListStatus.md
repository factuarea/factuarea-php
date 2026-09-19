# PublicApiV1StockTransfersListStatus

Stock transfer status. Four published values: `draft` (being written, it moves no stock), `dispatched` (the goods left the origin warehouse and are in transit), `received` (they arrived in full at the destination) and `cancelled` (terminal). A partially received transfer stays `dispatched` until the last unit arrives. Exact match on `status`.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Draft`      | draft        |
| `Dispatched` | dispatched   |
| `Received`   | received     |
| `Cancelled`  | cancelled    |