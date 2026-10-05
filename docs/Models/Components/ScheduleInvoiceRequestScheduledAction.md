# ScheduleInvoiceRequestScheduledAction

What happens at `scheduled_for`: `issue` issues the invoice without sending it; `issue_and_send` issues it and emails it to the customer. `draft` is still accepted as an alias of `issue` in every API version (it always meant "issue without sending").


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Issue`        | issue          |
| `IssueAndSend` | issue_and_send |