# RecurringInvoicePreviewDocumentTotals


## Fields

| Field                                                          | Type                                                           | Required                                                       | Description                                                    | Example                                                        |
| -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- |
| `subtotal`                                                     | *string*                                                       | :heavy_check_mark:                                             | Taxable base of the next invoice (EUR), as a 2-decimal string. | 200.00                                                         |
| `tax`                                                          | *string*                                                       | :heavy_check_mark:                                             | VAT of the next invoice (EUR), as a 2-decimal string.          | 42.00                                                          |
| `total`                                                        | *string*                                                       | :heavy_check_mark:                                             | Total of the next invoice (EUR), as a 2-decimal string.        | 242.00                                                         |