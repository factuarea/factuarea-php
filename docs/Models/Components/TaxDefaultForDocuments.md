# TaxDefaultForDocuments

Map `DocumentType -> bool` indicating for which document types this tax is default. Replaces the 5 legacy booleans `is_default_invoice/quote/delivery_note/proforma/purchase_invoice`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `invoice`          | *bool*             | :heavy_check_mark: | N/A                |
| `quote`            | *bool*             | :heavy_check_mark: | N/A                |
| `deliveryNote`     | *bool*             | :heavy_check_mark: | N/A                |
| `proforma`         | *bool*             | :heavy_check_mark: | N/A                |
| `purchaseInvoice`  | *bool*             | :heavy_check_mark: | N/A                |
| `recurringInvoice` | *bool*             | :heavy_check_mark: | N/A                |