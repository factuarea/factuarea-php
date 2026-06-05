# UsedBy

Count by consumer BC. Excludes `purchase_invoice_lines` (no FK) and `recurring_invoices` (JSON lines).


## Fields

| Field                                                                  | Type                                                                   | Required                                                               | Description                                                            |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `products`                                                             | *int*                                                                  | :heavy_check_mark:                                                     | `products.tax_id` referencias.                                         |
| `suppliers`                                                            | *int*                                                                  | :heavy_check_mark:                                                     | `suppliers.default_tax_id` referencias.                                |
| `invoiceLines`                                                         | *int*                                                                  | :heavy_check_mark:                                                     | `invoice_lines.{vat_id,retention_id,surcharge_id,tax_id}` referencias. |
| `quoteLines`                                                           | *int*                                                                  | :heavy_check_mark:                                                     | N/A                                                                    |
| `proformaLines`                                                        | *int*                                                                  | :heavy_check_mark:                                                     | N/A                                                                    |
| `deliveryNoteLines`                                                    | *int*                                                                  | :heavy_check_mark:                                                     | N/A                                                                    |