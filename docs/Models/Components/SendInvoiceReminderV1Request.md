# SendInvoiceReminderV1Request

Public REST API v1 — POST /v1/invoices/{uuid}/send-reminder
and POST /v1/invoices/{uuid}/reminder-preview (same fields).

All fields are optional — without overrides the handler uses the
canonical payment reminder template.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `email`            | *?string*          | :heavy_minus_sign: | N/A                |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `message`          | *?string*          | :heavy_minus_sign: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `bcc`              | array<*string*>    | :heavy_minus_sign: | N/A                |