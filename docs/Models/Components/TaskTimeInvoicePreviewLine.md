# TaskTimeInvoicePreviewLine


## Fields

| Field                                          | Type                                           | Required                                       | Description                                    |
| ---------------------------------------------- | ---------------------------------------------- | ---------------------------------------------- | ---------------------------------------------- |
| `description`                                  | *string*                                       | :heavy_check_mark:                             | Line text.                                     |
| `quantity`                                     | *string*                                       | :heavy_check_mark:                             | Hours, as a decimal string.                    |
| `unit`                                         | *string*                                       | :heavy_check_mark:                             | Unit code (`HUR`, hours).                      |
| `unitPrice`                                    | *string*                                       | :heavy_check_mark:                             | Hourly rate, as a decimal string.              |
| `amount`                                       | *string*                                       | :heavy_check_mark:                             | Line amount before taxes, as a decimal string. |