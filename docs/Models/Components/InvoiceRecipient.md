# InvoiceRecipient

Recipient of a complete invoice issued WITHOUT the recipient's tax ID (article 6.1.d of Royal Decree 1619/2012): name, address and country only, copied onto the invoice when it was issued. When it is present the invoice has no contact, so `client.id` is `null` and `client.name` repeats `recipient.name`. `null` on every invoice that has a client, which is the common case. Only the account's automatic invoicing creates this kind of invoice, and its VeriFactu record is `F2` (`R5` for its corrective).


## Fields

| Field                                                           | Type                                                            | Required                                                        | Description                                                     |
| --------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- |
| `name`                                                          | *string*                                                        | :heavy_check_mark:                                              | Name of the recipient.                                          |
| `address`                                                       | *string*                                                        | :heavy_check_mark:                                              | Street address of the recipient.                                |
| `postalCode`                                                    | *string*                                                        | :heavy_check_mark:                                              | N/A                                                             |
| `city`                                                          | *string*                                                        | :heavy_check_mark:                                              | N/A                                                             |
| `province`                                                      | *string*                                                        | :heavy_check_mark:                                              | N/A                                                             |
| `country`                                                       | *string*                                                        | :heavy_check_mark:                                              | ISO 3166-1 alpha-2 country code of the recipient, such as `US`. |