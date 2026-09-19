# StockTransferStatus

Lifecycle status: `draft` (being composed — the only status where lines can be added or removed), `dispatched` (the goods left the origin), `received` (everything dispatched arrived at the destination) and `cancelled` (terminal). Receiving PART of what was dispatched leaves the transfer in `dispatched` with the rest pending.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Draft`      | draft        |
| `Dispatched` | dispatched   |
| `Received`   | received     |
| `Cancelled`  | cancelled    |