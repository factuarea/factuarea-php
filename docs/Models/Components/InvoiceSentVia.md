# InvoiceSentVia

Channel of the first delivery: `email` (the mail server accepted the delivery email) or `manual` (marked with `POST /v1/invoices/{id}/mark-sent`). `null` while the invoice has not been delivered. Not present before API version `2026-10-01`.


## Values

| Name     | Value    |
| -------- | -------- |
| `Email`  | email    |
| `Manual` | manual   |