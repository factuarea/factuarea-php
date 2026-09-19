# StorefrontOrderInvoiceRef

Reference to the invoice issued for the order: its public identifier and its readable number, and nothing else. It travels as ONE object and never as two loose keys, because an order with no invoice has neither: two parallel null keys invite publishing one without the other. The internal key of the invoice is never published, and neither is its status — the invoice of an order is a document of the seller and what the buyer needs from it is how to name it.


## Fields

| Field                                                                   | Type                                                                    | Required                                                                | Description                                                             | Example                                                                 |
| ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| `id`                                                                    | *string*                                                                | :heavy_check_mark:                                                      | Public identifier of the issued invoice, a UUID v7.                     | 0199f2a3-f101-7a2b-8c3d-d0e1f2a3b451                                    |
| `number`                                                                | *string*                                                                | :heavy_check_mark:                                                      | Readable number of the issued invoice, as it was stamped by its series. | FAC-2026-00058                                                          |