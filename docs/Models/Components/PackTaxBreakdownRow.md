# PackTaxBreakdownRow

One VAT rate of the pack price. A pack whose components have mixed rates carries one row per rate.


## Fields

| Field                                          | Type                                           | Required                                       | Description                                    |
| ---------------------------------------------- | ---------------------------------------------- | ---------------------------------------------- | ---------------------------------------------- |
| `rate`                                         | *float*                                        | :heavy_check_mark:                             | VAT rate in percent, for example `21` or `10`. |
| `base`                                         | *string*                                       | :heavy_check_mark:                             | Taxable base at this rate.                     |
| `tax`                                          | *string*                                       | :heavy_check_mark:                             | VAT at this rate.                              |