# CreateInvoiceRequestLineType

Kind of line: `NORMAL` (default) for an ordinary line of your own operation, or `SUPLIDO` for a DISBURSEMENT — an amount you paid in the name and on behalf of the client (an official fee, duty or registry charge) and now re-invoice at cost. A disbursement is not part of your taxable base (art. 78.Tres.3 LIVA): it stays out of `subtotal`/`taxes_total`/`total`, is aggregated into `total_disbursements`, and is never declared in the AEAT VeriFactu record. A `SUPLIDO` line must carry no VAT, withholding, surcharge, discount, regime key, exemption cause or product, and is rejected on a simplified (`F2`) invoice.


## Values

| Name      | Value     |
| --------- | --------- |
| `Normal`  | NORMAL    |
| `Suplido` | SUPLIDO   |