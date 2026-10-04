# SeriesInvoiceKind

Fixed purpose of an invoice series (RD 1619/2012 arts. 6.1.a, 7.1.a and 15): `complete` (complete invoices `F1` and the `F3` that replaces simplified invoices — the usual series), `simplified` (simplified invoices `F2`), `corrective` (corrective invoices `R1`–`R4`) or `simplified_corrective` (corrective invoices of simplified invoices `R5`). An invoice can only be issued in a series of its own purpose, otherwise issuing fails with `series_invoice_kind_mismatch`. Set on creation (default `complete`), editable with `PUT /v1/series/{id}` until the series has any invoice (`series_invoice_kind_locked`) and never on the default series of its purpose (`series_default_kind_change`). `null` for series of other document types.


## Values

| Name                   | Value                  |
| ---------------------- | ---------------------- |
| `Complete`             | complete               |
| `Simplified`           | simplified             |
| `Corrective`           | corrective             |
| `SimplifiedCorrective` | simplified_corrective  |