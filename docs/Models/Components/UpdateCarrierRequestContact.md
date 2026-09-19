# UpdateCarrierRequestContact

Operational contact of the carrier. Sending it REPLACES the whole block and `null` withdraws it: there is no way to change just the phone number, because the three fields describe one person and a partial change would silently keep the name of somebody who is no longer there.


## Fields

| Field                                                 | Type                                                  | Required                                              | Description                                           |
| ----------------------------------------------------- | ----------------------------------------------------- | ----------------------------------------------------- | ----------------------------------------------------- |
| `name`                                                | *?string*                                             | :heavy_minus_sign:                                    | Person to call about a shipment                       |
| `email`                                               | *?string*                                             | :heavy_minus_sign:                                    | Email address of the person to call about a shipment. |
| `phone`                                               | *?string*                                             | :heavy_minus_sign:                                    | Phone number of that person.                          |