# SendSalesOrderV1Request

Send the confirmation of a sales order to its buyer by email, with the printable document attached. `to` is the list of recipient addresses, `cc` and `bcc` are optional lists, `subject` (up to 200 characters) and `body` (up to 5000) override the defaults of the template. Every list validates its entries as email addresses, and none of them is checked against your contacts: sending the document to an address that is not on file is a legitimate case. NO language field is accepted — the language of the message is sealed from the issuing company, so the same document always leaves in the same language whatever the caller asks. The answer is 202 with an acknowledgement that the email was accepted and QUEUED, never that it was delivered. When `to` is omitted the order falls back to the email of its buyer; an order with no buyer email and no `to` is rejected with 422 and the missing parameter named.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `to`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `bcc`              | array<*string*>    | :heavy_minus_sign: | N/A                |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `body`             | *?string*          | :heavy_minus_sign: | N/A                |