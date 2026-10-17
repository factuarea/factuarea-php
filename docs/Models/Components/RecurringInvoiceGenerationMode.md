# RecurringInvoiceGenerationMode

What each run of the recurrence does with the invoice it generates: `draft` leaves it as a draft (no definitive number); `issue` issues it (definitive number, VeriFactu record, stock movements) without emailing it, so it stays `is_sent = false`; `issue_and_send` issues it and emails it to the configured recipients, and it is marked as sent once the mail server accepts the email (if the delivery fails the invoice stays issued and not sent). Available in every API version. `send_automatically` is derived from it.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Draft`        | draft          |
| `Issue`        | issue          |
| `IssueAndSend` | issue_and_send |