# TaxTotalsLine

Calculated breakdown of a single line within `POST /v1/taxes/calculate-totals`.


## Fields

| Field                                                       | Type                                                        | Required                                                    | Description                                                 | Example                                                     |
| ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- | ----------------------------------------------------------- |
| `subtotal`                                                  | *float*                                                     | :heavy_check_mark:                                          | Taxable base of the line (quantity × price − discount).     | 100                                                         |
| `vatAmount`                                                 | *float*                                                     | :heavy_check_mark:                                          | VAT amount of the line.                                     | 21                                                          |
| `surchargeAmount`                                           | *float*                                                     | :heavy_check_mark:                                          | Equivalence surcharge amount of the line.                   | 0                                                           |
| `retentionAmount`                                           | *float*                                                     | :heavy_check_mark:                                          | Withholding (IRPF) amount of the line.                      | 0                                                           |
| `total`                                                     | *float*                                                     | :heavy_check_mark:                                          | Total of the line (subtotal + vat + surcharge − retention). | 121                                                         |