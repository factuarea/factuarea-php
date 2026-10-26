# EventDataProformaEmailSent

Payload (`data`) emitted with the `proforma.email_sent` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                        | Type                                                         | Required                                                     | Description                                                  |
| ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
| `type`                                                       | *string*                                                     | :heavy_check_mark:                                           | N/A                                                          |
| `object`                                                     | [Components\Proforma](../../Models/Components/Proforma.md)   | :heavy_check_mark:                                           | A proforma invoice that can be converted to a final invoice. |
| `recipientEmail`                                             | *string*                                                     | :heavy_check_mark:                                           | N/A                                                          |
| `proformaNumber`                                             | *string*                                                     | :heavy_check_mark:                                           | N/A                                                          |
| `cc`                                                         | array<*string*>                                              | :heavy_check_mark:                                           | N/A                                                          |
| `bcc`                                                        | array<*string*>                                              | :heavy_check_mark:                                           | N/A                                                          |