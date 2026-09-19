# SendStockTransferV1Request

Send the note of a stock transfer by email, with the printable document attached. `to` is the list of recipient addresses, `cc` and `bcc` are optional lists, `subject` (up to 200 characters) and `body` (up to 5000) override the defaults of the template. Every list validates its entries as email addresses, and none of them is checked against your contacts: sending the document to an address that is not on file is a legitimate case. NO language field is accepted — the language of the message is sealed from the issuing company, so the same document always leaves in the same language whatever the caller asks. The answer is 202 with an acknowledgement that the email was accepted and QUEUED, never that it was delivered. `to` is REQUIRED here and optional on the other two document families, and that asymmetry is the one thing to read before wiring your integration: a warehouse is not a contact and carries no email address, so there is no default recipient to fall back on. Omitting it is rejected at the border with 422 and `param: to`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `to`               | array<*string*>    | :heavy_check_mark: | N/A                |
| `cc`               | array<*string*>    | :heavy_minus_sign: | N/A                |
| `bcc`              | array<*string*>    | :heavy_minus_sign: | N/A                |
| `subject`          | *?string*          | :heavy_minus_sign: | N/A                |
| `body`             | *?string*          | :heavy_minus_sign: | N/A                |