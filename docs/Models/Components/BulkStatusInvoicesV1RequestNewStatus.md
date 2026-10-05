# BulkStatusInvoicesV1RequestNewStatus

Target status: `issued` issues each draft (definitive number, VeriFactu record) without sending any email; `paid` records a payment for the outstanding amount of each issued invoice. `sent` is accepted as an alias of `issued` in every API version and never sets the delivery mark (`is_sent`).


## Values

| Name     | Value    |
| -------- | -------- |
| `Issued` | issued   |
| `Sent`   | sent     |
| `Paid`   | paid     |