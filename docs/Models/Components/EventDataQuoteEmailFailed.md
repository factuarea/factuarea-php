# EventDataQuoteEmailFailed

Payload (`data`) emitted with the `quote.email_failed` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `type`                                               | *string*                                             | :heavy_check_mark:                                   | N/A                                                  |
| `object`                                             | [Components\Quote](../../Models/Components/Quote.md) | :heavy_check_mark:                                   | A sales quote that can be converted to an invoice.   |
| `recipientEmail`                                     | *string*                                             | :heavy_check_mark:                                   | N/A                                                  |
| `errorMessage`                                       | *string*                                             | :heavy_check_mark:                                   | N/A                                                  |
| `errorClass`                                         | *string*                                             | :heavy_check_mark:                                   | N/A                                                  |
| `cc`                                                 | array<*string*>                                      | :heavy_check_mark:                                   | N/A                                                  |
| `bcc`                                                | array<*string*>                                      | :heavy_check_mark:                                   | N/A                                                  |