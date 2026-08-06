# UpdateCompanyV1Request

Partial update of a managed child company profile (business name, fiscal address, contact details). Omitted fields keep their current value; send `""` to clear a field. `country_aeat_zone` is derived from the postal code, and `tax_id` is immutable after creation (sending it returns 422).


## Fields

| Field                                                | Type                                                 | Required                                             | Description                                          |
| ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- | ---------------------------------------------------- |
| `name`                                               | *?string*                                            | :heavy_minus_sign:                                   | Trade name of the child company (1-255 characters).  |
| `businessName`                                       | *?string*                                            | :heavy_minus_sign:                                   | Legal/registered business name of the child company. |
| `address`                                            | *?string*                                            | :heavy_minus_sign:                                   | Fiscal address.                                      |
| `city`                                               | *?string*                                            | :heavy_minus_sign:                                   | City of the fiscal address.                          |
| `postalCode`                                         | *?string*                                            | :heavy_minus_sign:                                   | Postal code (derives the AEAT zone).                 |
| `province`                                           | *?string*                                            | :heavy_minus_sign:                                   | Province.                                            |
| `country`                                            | *?string*                                            | :heavy_minus_sign:                                   | Country.                                             |
| `email`                                              | *?string*                                            | :heavy_minus_sign:                                   | Contact email of the child company.                  |
| `phone`                                              | *?string*                                            | :heavy_minus_sign:                                   | Contact phone number.                                |