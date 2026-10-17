# SendTransportDocumentRequest

Email the current PDF of the transport document. Optional `to`, `cc` and `bcc` (lists of emails, up to 5 distinct addresses in total; an address repeated within or across the lists, ignoring letter case, counts and is sent once) plus `subject` and `message`. Without `to`, the email of the carrier contact is used.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `message`          | *?string*          | :heavy_minus_sign: | N/A                |
| `to`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `bcc`              | array<*string*>    | :heavy_minus_sign: | N/A                |