# UpdateInvoiceRequestLineType

Kind of line: `NORMAL` (default) for an ordinary line of your own operation, or `SUPLIDO` for a DISBURSEMENT — an amount you paid in the name and on behalf of the client and now re-invoice at cost, which stays out of the taxable base (art. 78.Tres.3 LIVA). Replacing the lines of a draft re-applies the same rules, so keep the field when you resend a line you read from the invoice: sending it as `NORMAL` (or omitting it) turns the disbursement into an ordinary taxable line and changes the invoice amount.


## Values

| Name      | Value     |
| --------- | --------- |
| `Normal`  | NORMAL    |
| `Suplido` | SUPLIDO   |