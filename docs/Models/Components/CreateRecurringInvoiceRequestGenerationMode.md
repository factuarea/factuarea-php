# CreateRecurringInvoiceRequestGenerationMode

What each run of the recurrence does with the invoice it generates: `draft` leaves it as a draft; `issue` issues it (definitive number, VeriFactu record, stock movements) without emailing it; `issue_and_send` issues it and emails it to the `auto_delivery` recipients. Any other value is rejected with 422. Optional and nullable: when omitted (or `null`) the mode is derived from `send_automatically` (`true` → `issue_and_send`, `false` or absent → `draft`). When sent, it takes precedence and `send_automatically` becomes a derived field (`true` only with `issue_and_send`); sending a `send_automatically` (top-level or inside `auto_delivery`) that contradicts it is rejected with 422. Recipients are only required with `issue_and_send`: if an `auto_delivery` object is configured, `auto_delivery.recipients` must then contain at least one address (422 `auto_delivery_recipients_required` otherwise); `draft` and `issue` send no email and need none. Available in every API version.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Draft`        | draft          |
| `Issue`        | issue          |
| `IssueAndSend` | issue_and_send |