# Suppliers347


## Fields

| Field                                               | Type                                                | Required                                            | Description                                         |
| --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- |
| `taxId`                                             | *?string*                                           | :heavy_minus_sign:                                  | NIF/CIF del proveedor.                              |
| `partyName`                                         | *?string*                                           | :heavy_minus_sign:                                  | Name or legal/business name of the supplier.        |
| `base`                                              | *?int*                                              | :heavy_minus_sign:                                  | Total annual amount of operations, in cents.        |
| `quarters`                                          | array<string, *int*>                                | :heavy_minus_sign:                                  | Amount per quarter (quarter key → amount in cents). |