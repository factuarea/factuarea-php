# UpdateRecurringInvoiceRequestGenerationMode

New generation mode of the recurrence: `draft` (generated invoices stay as drafts), `issue` (they are issued without being emailed) or `issue_and_send` (they are issued and emailed to the `auto_delivery` recipients). Any other value is rejected with 422. Optional: when omitted (or `null`) the current mode is kept, unless `send_automatically` is sent on its own and changes whether the recurrence emails its invoices (`true` switches a non-sending mode to `issue_and_send`; `false` switches `issue_and_send` to `draft`). When sent, it takes precedence and `send_automatically` becomes a derived field (`true` only with `issue_and_send`); sending a `send_automatically` (top-level or inside `auto_delivery`) that contradicts it is rejected with 422. Recipients are only required with `issue_and_send`: if the recurrence has an `auto_delivery` configuration, it must keep at least one recipient (422 `auto_delivery_recipients_required` otherwise). Available in every API version.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Draft`        | draft          |
| `Issue`        | issue          |
| `IssueAndSend` | issue_and_send |