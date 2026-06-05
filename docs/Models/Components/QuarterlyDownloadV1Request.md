# QuarterlyDownloadV1Request

Public REST API v1 — POST /v1/invoices/quarterly/download-zip
and POST /v1/invoices/quarterly/send-email (shared fields).

The canonical recipient field is `email`. We accept
`to_email` as a deprecated alias for compatibility with older clients.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `year`             | *int*              | :heavy_check_mark: | N/A                |
| `quarter`          | *int*              | :heavy_check_mark: | N/A                |
| `includeIndex`     | *?bool*            | :heavy_minus_sign: | N/A                |
| `email`            | *?string*          | :heavy_minus_sign: | N/A                |
| `message`          | *?string*          | :heavy_minus_sign: | N/A                |