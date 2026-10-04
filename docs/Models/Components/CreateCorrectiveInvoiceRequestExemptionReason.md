# CreateCorrectiveInvoiceRequestExemptionReason

Cause of VAT exemption of the corrected line (the closed catalog of `POST /v1/invoices`). OMIT it and the line inherits the exemption of the original line at the same index; SEND it and it replaces it; send `null` for explicitly none — for example, `exemption_reason: null` turns an exempt original line into a taxed one.


## Values

| Name  | Value |
| ----- | ----- |
| `E1`  | E1    |
| `E2`  | E2    |
| `E3`  | E3    |
| `E4`  | E4    |
| `E5`  | E5    |
| `E6`  | E6    |
| `N1`  | N1    |
| `N2`  | N2    |