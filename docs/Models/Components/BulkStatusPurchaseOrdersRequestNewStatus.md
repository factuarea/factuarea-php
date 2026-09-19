# BulkStatusPurchaseOrdersRequestNewStatus

Target status of the transition: `sent` (the supplier has the document), `confirmed` (the supplier accepted it) or `cancelled`. Those three are the only ones that can be requested. `draft` is the status an order is born in and has no incoming transition; `partially_received`, `received` and `billed` are DERIVED — the first two from posted goods receipts, the third from the supplier-invoice match — so they are never a target; and closing an order is not a status, it is a separate operation on a single order.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Sent`      | sent        |
| `Confirmed` | confirmed   |
| `Cancelled` | cancelled   |