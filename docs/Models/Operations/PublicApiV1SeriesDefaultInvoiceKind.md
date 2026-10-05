# PublicApiV1SeriesDefaultInvoiceKind

Purpose of the invoice series whose default is requested (only with `document_type=invoice`). Defaults to `complete`. Returns 404 when the company has no series of that purpose yet (it is created automatically on the first issue) and 422 `series_invoice_kind_invalid` for a value outside the list.


## Values

| Name                   | Value                  |
| ---------------------- | ---------------------- |
| `Complete`             | complete               |
| `Simplified`           | simplified             |
| `Corrective`           | corrective             |
| `SimplifiedCorrective` | simplified_corrective  |