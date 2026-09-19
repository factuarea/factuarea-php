# CreateWarehouseRequestAddress

Postal address of the warehouse, under the same keys the response publishes it with.


## Fields

| Field                                                                 | Type                                                                  | Required                                                              | Description                                                           |
| --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- |
| `line1`                                                               | *?string*                                                             | :heavy_minus_sign:                                                    | Street and number.                                                    |
| `line2`                                                               | *?string*                                                             | :heavy_minus_sign:                                                    | Second address line: unit, floor or door. Optional.                   |
| `city`                                                                | *?string*                                                             | :heavy_minus_sign:                                                    | Town or city.                                                         |
| `province`                                                            | *?string*                                                             | :heavy_minus_sign:                                                    | Province.                                                             |
| `postalCode`                                                          | *?string*                                                             | :heavy_minus_sign:                                                    | Postal code.                                                          |
| `country`                                                             | *?string*                                                             | :heavy_minus_sign:                                                    | Country as an ISO 3166-1 alpha-2 code. Defaults to `ES` when omitted. |