# CreatePurchaseInvoiceRequestStatus

Optional initial status (BR-PUR-001). 4-state model:
CREATION allowlist `draft|pending` (`received`/`pending_payment`
were merged into `pending`). `paid|cancelled` are lifecycle
transitions (mark_paid/change_status), NOT creation states.
If omitted, the domain applies the default `draft`.


## Values

| Name      | Value     |
| --------- | --------- |
| `Draft`   | draft     |
| `Pending` | pending   |