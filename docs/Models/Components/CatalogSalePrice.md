# CatalogSalePrice

A sale price in euros split into net, VAT and gross. `net` keeps the scale of the catalog price (two to four decimals); `tax` and `gross` have two decimals and `net` + `tax` = `gross`.


## Fields

| Field                                                   | Type                                                    | Required                                                | Description                                             |
| ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- | ------------------------------------------------------- |
| `net`                                                   | *string*                                                | :heavy_check_mark:                                      | Price without VAT, with the scale of the catalog price. |
| `tax`                                                   | *string*                                                | :heavy_check_mark:                                      | VAT of the price.                                       |
| `gross`                                                 | *string*                                                | :heavy_check_mark:                                      | Price with VAT.                                         |