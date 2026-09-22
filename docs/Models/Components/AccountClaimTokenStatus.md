# AccountClaimTokenStatus

DERIVED state, read against the clock: the table has no status column. A consumed token and one that never existed are deliberately indistinguishable on redemption.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Active`   | active     |
| `Expired`  | expired    |
| `Consumed` | consumed   |