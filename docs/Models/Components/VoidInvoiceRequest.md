# VoidInvoiceRequest

Public REST API v1 — POST /v1/companies/{company}/invoices/{invoice}/void.

Optional body: `reason` (string). If the API client does not send a
reason, a placeholder is persisted ("Anulada via API v1.").


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `reason`           | *?string*          | :heavy_minus_sign: | N/A                |