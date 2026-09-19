# SeriesBootstrapEntryDocumentType

Document type this entry reports on. The `contract` type is outside the public surface and is never bootstrapped, and neither are `sales_order` and `purchase_order`: the public API accepts creating a sales order or a purchase order series, but the bootstrap does not create one.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Invoice`      | invoice        |
| `Quote`        | quote          |
| `DeliveryNote` | delivery_note  |
| `Proforma`     | proforma       |