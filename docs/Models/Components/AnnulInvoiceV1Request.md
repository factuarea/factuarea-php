# AnnulInvoiceV1Request

Public REST API v1 — POST /v1/invoices/{uuid}/annul.

Body: `reason` (string, required, max. 500). The reason is persisted
on the Invoice aggregate and included in the VeriFactu cancellation record
(AnulacionRecord) when applicable.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `reason`           | *string*           | :heavy_check_mark: | N/A                |