# FindInvoiceByNumberRequest

Public REST API v1 — POST /v1/invoices/find-by-number.

Body: `number` (invoice number, required), `year` optional to
disambiguate when the number repeats across fiscal years. Aligned with
Supplier's `find-by-tax-id`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `number`           | *string*           | :heavy_check_mark: | N/A                |
| `year`             | *?int*             | :heavy_minus_sign: | N/A                |