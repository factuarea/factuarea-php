# InvoiceWithCheckoutBlocksScheduledAction

Action the scheduler runs when `scheduled_for` is reached: `issue` (issue without sending) or `issue_and_send` (issue and email it). `null` when the invoice is not scheduled. Before API version `2026-10-01` the `issue` action is published as `draft`, its previous name.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Issue`        | issue          |
| `IssueAndSend` | issue_and_send |
| `Draft`        | draft          |