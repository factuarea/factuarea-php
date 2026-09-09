# BulkSendProformasV1Request

Email several proformas in one request (queued), up to 200 per batch. `ids` is an array of proforma UUIDs; the optional `to`/`cc` arrays and `subject`/`message`/`language` overrides apply to the whole batch (when `to` is omitted, each proforma uses its client email).


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |
| `to`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `message`          | *?string*          | :heavy_minus_sign: | N/A                |
| `language`         | *?string*          | :heavy_minus_sign: | N/A                |