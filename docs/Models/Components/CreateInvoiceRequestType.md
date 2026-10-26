# CreateInvoiceRequestType

Invoice type: `F1` (complete invoice, the default) or `F2` (simplified invoice, the ticket of a terminal). A simplified invoice needs simplified invoices enabled for the company (422 `simplified_invoices_disabled`) and cannot exceed the absolute cap of 3,000 € VAT included (422 `simplified_invoice_not_allowed`).


## Values

| Name  | Value |
| ----- | ----- |
| `F1`  | F1    |
| `F2`  | F2    |