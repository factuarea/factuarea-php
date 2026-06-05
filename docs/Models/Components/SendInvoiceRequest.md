# SendInvoiceRequest

Public REST API v1 — POST /v1/invoices/{uuid}/send.

Optional body: `to` (string), `cc[]`, `bcc[]` (arrays of emails),
`subject` (max 200), `body` (string). The controller performs the
cross-field validation: if the client has no email and `to` is
absent, it returns 422 `missing_required_param`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `to`               | *?string*          | :heavy_minus_sign: | N/A                |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `body`             | *?string*          | :heavy_minus_sign: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `bcc`              | array<*string*>    | :heavy_minus_sign: | N/A                |