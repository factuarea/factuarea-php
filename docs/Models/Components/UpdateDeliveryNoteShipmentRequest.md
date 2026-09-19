# UpdateDeliveryNoteShipmentRequest

Partial update of the shipment of a delivery note.

## What partial means here

Every property is optional and the body may arrive empty: a body with no
properties changes nothing and is not an error. A property you do not send
keeps the value it has; a property you send with `null` is REMOVED. What
tells those two apart is whether the KEY is present in the body, never the
value it carries, and an empty string is read as the same order as `null`.
There is no full-replacement operation for the shipment: this is the only way
to edit it.

## What this body accepts

 - `carrier_id` — public identifier of a carrier of the same company. `null`
   detaches the carrier, and with it the tracking URL that is derived from
   its template. A carrier held by another company is rejected exactly like
   one that does not exist, with no way to tell the two apart. A carrier that
   is no longer active is rejected as a business rule violation, and the
   shipment keeps the carrier it already had.
 - `expected_delivery_date` — expected delivery date, as `YYYY-MM-DD` with no
   time part. `null` removes it.
 - `packages_reference` — the reference the carrier prints for the shipment
   as a whole, up to 60 characters. `null` removes it.

## What this body does not accept, and where those values are changed

The tracking number and the free carrier text of the delivery note are NOT
written here. Both keep a single writing path, the update of the delivery
note itself, so that one value cannot be moved by two operations that could
disagree; the shipment reading keeps publishing both, and the tracking URL it
publishes is built from the tracking number every time it is read.

The stage of the physical cycle is not written here either: it moves through
its own operation, which applies the transition matrix. Packages are their own
sub-resource, and the total weight of the shipment is always the sum of them,
so neither the package count nor the total weight is accepted as a property.


## Fields

| Field                                                                                                                                                                                                                                                                                                                                                                     | Type                                                                                                                                                                                                                                                                                                                                                                      | Required                                                                                                                                                                                                                                                                                                                                                                  | Description                                                                                                                                                                                                                                                                                                                                                               |
| ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `carrierId`                                                                                                                                                                                                                                                                                                                                                               | *?string*                                                                                                                                                                                                                                                                                                                                                                 | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                        | Public ID of the carrier of the shipment, from your carrier master data. Omitting it keeps the current one and `null` DETACHES it. A carrier of another company is rejected exactly like one that does not exist, naming this field, so the rejection never tells you whether it exists.                                                                                  |
| `expectedDeliveryDate`                                                                                                                                                                                                                                                                                                                                                    | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                                                                                                                                                                                                                                                                                             | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                        | Date the goods are expected to reach the recipient, as a calendar date `YYYY-MM-DD` with no time of day. Omitting it keeps the current one and `null` removes it. It is what the preparation queue is prioritized by, so leaving it empty takes the delivery note out of that ordering rather than putting it last.                                                       |
| `packagesReference`                                                                                                                                                                                                                                                                                                                                                       | *?string*                                                                                                                                                                                                                                                                                                                                                                 | :heavy_minus_sign:                                                                                                                                                                                                                                                                                                                                                        | Reference the carrier prints for the WHOLE shipment — the consignment note or manifest number — as opposed to the label of each individual package. Omitting it keeps the current one and `null` removes it. The TRACKING NUMBER is not a field of this operation and sending it changes nothing: it is written with the operation that updates the delivery note itself. |