# WarehouseAddress

Address of the warehouse, broken down. It is the shape of the shared address value object of the contract, so it carries exactly these six fields and not the structured extras the client and supplier address publishes.


## Fields

| Field                                                              | Type                                                               | Required                                                           | Description                                                        |
| ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ |
| `line1`                                                            | *string*                                                           | :heavy_check_mark:                                                 | Street and number.                                                 |
| `line2`                                                            | *string*                                                           | :heavy_check_mark:                                                 | Second address line (building, industrial estate, bay), or `null`. |
| `city`                                                             | *string*                                                           | :heavy_check_mark:                                                 | Town or city.                                                      |
| `province`                                                         | *string*                                                           | :heavy_check_mark:                                                 | Province.                                                          |
| `postalCode`                                                       | *string*                                                           | :heavy_check_mark:                                                 | Postal code.                                                       |
| `country`                                                          | *string*                                                           | :heavy_check_mark:                                                 | ISO 3166-1 alpha-2 country code.                                   |