# CarrierContact

Contact details of the carrier. The whole OBJECT is ABSENT — not `null` — when none of its three fields is recorded, because the object is the unit: with three flat fields, "no contact" would be three loose nulls. When it does travel, its three keys always travel, `null` on the ones that are not recorded. It is NOT a reference to a business contact: a business contact is a billable entity with a life of its own, and publishing it as a reference would force you to create one just to register a carrier.


## Fields

| Field                                                            | Type                                                             | Required                                                         | Description                                                      |
| ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- | ---------------------------------------------------------------- |
| `name`                                                           | *string*                                                         | :heavy_check_mark:                                               | Name of the person or desk to talk to at the carrier, or `null`. |
| `email`                                                          | *string*                                                         | :heavy_check_mark:                                               | Contact email address of the carrier, or `null`.                 |
| `phone`                                                          | *string*                                                         | :heavy_check_mark:                                               | Contact phone number of the carrier, or `null`.                  |