# UpdateConnectedAccountRequestDocumentLanguage

Language of the documents auto-created from this account: `es`, `en` or `ca`. Each invoice the account issues is born with it, and its PDF and email follow it. Three states: omitted leaves it unchanged, `null` clears it (new invoices carry no language of their own and the company issuing language applies, which is the default) and a language sets it. A value outside the catalog is rejected with 422 on `document_language`. Invoices already issued keep the language they were born with.


## Values

| Name  | Value |
| ----- | ----- |
| `Es`  | es    |
| `En`  | en    |
| `Ca`  | ca    |