# Clients347


## Fields

| Field                                               | Type                                                | Required                                            | Description                                         |
| --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- | --------------------------------------------------- |
| `taxId`                                             | *?string*                                           | :heavy_minus_sign:                                  | NIF/CIF del cliente.                                |
| `partyName`                                         | *?string*                                           | :heavy_minus_sign:                                  | Name or legal/business name of the client.          |
| `base`                                              | *?int*                                              | :heavy_minus_sign:                                  | Total annual amount of operations, in cents.        |
| `quarters`                                          | array<string, *int*>                                | :heavy_minus_sign:                                  | Amount per quarter (quarter key → amount in cents). |