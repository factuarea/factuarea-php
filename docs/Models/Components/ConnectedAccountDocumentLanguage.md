# ConnectedAccountDocumentLanguage

Language of the documents that auto-invoicing creates from this account: `es`, `en` or `ca`. Each invoice is born with it and its PDF and email follow it. `null` when none is configured (the default): invoices carry no language of their own and the company issuing language applies. Invoices already issued keep the language they were born with. Editable with `PATCH /v1/companies/{company}/connected-accounts/{account}`.


## Values

| Name  | Value |
| ----- | ----- |
| `Es`  | es    |
| `En`  | en    |
| `Ca`  | ca    |