# CreateCarrierRequestContact

Operational contact of the carrier — who to call about a shipment — published back under the same keys it is sent with. It travels as ONE block: sending it replaces the three fields at once, so a field you leave out inside the block ends up empty rather than keeping its previous value.


## Fields

| Field                                                 | Type                                                  | Required                                              | Description                                           |
| ----------------------------------------------------- | ----------------------------------------------------- | ----------------------------------------------------- | ----------------------------------------------------- |
| `name`                                                | *?string*                                             | :heavy_minus_sign:                                    | Person to call about a shipment                       |
| `email`                                               | *?string*                                             | :heavy_minus_sign:                                    | Email address of the person to call about a shipment. |
| `phone`                                               | *?string*                                             | :heavy_minus_sign:                                    | Phone number of that person.                          |