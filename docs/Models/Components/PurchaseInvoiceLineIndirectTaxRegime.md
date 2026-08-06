# PurchaseInvoiceLineIndirectTaxRegime

Indirect tax regime override of the supplier line: `iva`/`igic`/`ipsi` when set per-document (precedence override>zone), otherwise `null`. Writable on create/update; the document must be homogeneous (a single non-null regime across all lines, 422 otherwise).


## Values

| Name   | Value  |
| ------ | ------ |
| `Iva`  | iva    |
| `Igic` | igic   |
| `Ipsi` | ipsi   |