# SendQuoteRequest

Email a quote. Optional body: `to` (string), `cc[]` and `bcc[]` (arrays of emails), `subject` (max 200 characters) and `body` (string). If the client has no email and `to` is absent, it returns 422 `missing_required_param`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `to`               | *?string*          | :heavy_minus_sign: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `bcc`              | array<*string*>    | :heavy_minus_sign: | N/A                |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `body`             | *?string*          | :heavy_minus_sign: | N/A                |