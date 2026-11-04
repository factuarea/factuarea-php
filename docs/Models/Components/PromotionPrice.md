# PromotionPrice

A price in euros split into net, VAT and gross. The three amounts are decimal strings with two decimals and always add up: `net` + `tax` = `gross`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `net`              | *string*           | :heavy_check_mark: | Price without VAT. |
| `tax`              | *string*           | :heavy_check_mark: | VAT of the price.  |
| `gross`            | *string*           | :heavy_check_mark: | Price with VAT.    |