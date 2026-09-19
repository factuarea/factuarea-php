# BillingAddress

Billing address of the buyer. It is independent of the shipping address: changing one does not drag the other.


## Fields

| Field                                                                | Type                                                                 | Required                                                             | Description                                                          |
| -------------------------------------------------------------------- | -------------------------------------------------------------------- | -------------------------------------------------------------------- | -------------------------------------------------------------------- |
| `addressLine`                                                        | *string*                                                             | :heavy_check_mark:                                                   | Street and number, or `null` when not reported.                      |
| `city`                                                               | *string*                                                             | :heavy_check_mark:                                                   | Town or city, or `null` when not reported.                           |
| `province`                                                           | *string*                                                             | :heavy_check_mark:                                                   | Province, or `null` when not reported.                               |
| `postalCode`                                                         | *string*                                                             | :heavy_check_mark:                                                   | Postal code, or `null` when not reported.                            |
| `country`                                                            | *string*                                                             | :heavy_check_mark:                                                   | Country, free text and not an ISO code, or `null` when not reported. |