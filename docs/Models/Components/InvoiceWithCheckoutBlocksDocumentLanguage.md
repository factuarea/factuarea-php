# InvoiceWithCheckoutBlocksDocumentLanguage

Own language of the invoice (`es`, `en` or `ca`): fixed when the account's automatic invoicing creates it and read-only, so no write operation accepts it. `null` when the invoice has no language of its own, which is the common case: its PDF and its email then come out in the company's issuing language.


## Values

| Name  | Value |
| ----- | ----- |
| `Es`  | es    |
| `En`  | en    |
| `Ca`  | ca    |