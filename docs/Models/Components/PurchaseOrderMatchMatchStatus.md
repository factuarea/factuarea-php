# PurchaseOrderMatchMatchStatus

Match status of the SET of linked invoices: `not_applicable` when there is none, `deviation` as soon as ONE of them deviates (a deviation is not compensated by an invoice that adds up), the shared status when all of them agree, and `pending` on any other mix — the match of the order is not settled while one of its invoices is not.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `NotApplicable` | not_applicable  |
| `Pending`       | pending         |
| `Matched`       | matched         |
| `Deviation`     | deviation       |
| `Rejected`      | rejected        |