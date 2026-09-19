# StorefrontAddress

A postal address of the buyer. Its five keys ALWAYS travel, `null` on the ones that are not filled in: an address with half its fields empty is a real state, not a different shape.


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          | Example                                              |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `addressLine`                                        | *string*                                             | :heavy_check_mark:                                   | Street, number and any additional detail, or `null`. | Calle Mayor 12, 3º B                                 |
| `city`                                               | *string*                                             | :heavy_check_mark:                                   | Town or city, or `null`.                             | Alicante                                             |
| `province`                                           | *string*                                             | :heavy_check_mark:                                   | Province or region, or `null`.                       | Alicante                                             |
| `postalCode`                                         | *string*                                             | :heavy_check_mark:                                   | Postal code, or `null`.                              | 03001                                                |
| `country`                                            | *string*                                             | :heavy_check_mark:                                   | Country, or `null`.                                  | ES                                                   |