# NifTransferStatus

DERIVED state, read against the clock: the table has no status column. `revoked` means the issuing account withdrew it before anyone accepted it. A consumed or revoked transfer and one that never existed are deliberately indistinguishable on acceptance.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Active`   | active     |
| `Expired`  | expired    |
| `Revoked`  | revoked    |
| `Consumed` | consumed   |