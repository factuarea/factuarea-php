# PurchaseInvoiceMatchMatchStatus

Match status of the invoice: `not_applicable` when it is not linked to any order, `pending` while it has not been evaluated, `matched` when everything adds up, `deviation` when it does not, and `rejected` when the deviation was rejected. Accepting a deviation resolves it to `matched`, and that leaves the ORDER invoiced — a terminal status no operation of this API reopens.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `NotApplicable` | not_applicable  |
| `Pending`       | pending         |
| `Matched`       | matched         |
| `Deviation`     | deviation       |
| `Rejected`      | rejected        |