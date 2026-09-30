# InvoiceStatus

Public invoice status. `issued` means the invoice has been ISSUED (definitive number, VeriFactu record); it says nothing about delivery, which lives in `is_sent`/`sent_at`/`sent_via`. `paid` and `partially_paid` are derived from the payment ledger. Before API version `2026-10-01` the issued status is published as `sent` (that contract called issuing "sending"), so `sent` only appears for integrations pinned to an earlier version.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `Draft`         | draft           |
| `Scheduled`     | scheduled       |
| `Issued`        | issued          |
| `Paid`          | paid            |
| `PartiallyPaid` | partially_paid  |
| `Overdue`       | overdue         |
| `Cancelled`     | cancelled       |
| `Annulled`      | annulled        |
| `Sent`          | sent            |