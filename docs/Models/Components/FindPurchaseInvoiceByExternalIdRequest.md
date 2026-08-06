# FindPurchaseInvoiceByExternalIdRequest

Look up a purchase invoice by its `external_id` (the integration key that maps it to a record in a third-party ERP/CRM/e-commerce system) within your company. Orthogonal to `external_invoice_number`, the supplier fiscal number.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `externalId`       | *string*           | :heavy_check_mark: | N/A                |