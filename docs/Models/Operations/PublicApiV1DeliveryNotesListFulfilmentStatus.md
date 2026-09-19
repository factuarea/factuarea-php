# PublicApiV1DeliveryNotesListFulfilmentStatus

State of the PHYSICAL axis of the delivery note: `pending` (nothing prepared yet), `picking` (being prepared), `prepared` (ready to hand over), `handed_over` (the goods are with the carrier), `in_transit`, `delivered` (the recipient has them) and `failed` (the delivery attempt did not succeed). It is ORTHOGONAL to `status`, which is the commercial state of the document: a delivery note already `delivered` to the recipient may still be a draft commercially, and the word `delivered` means different things on each axis. A delivery note that never entered the physical cycle reads `pending`, so this filter never leaves one out for lack of a value. Exact match on `fulfilment_status`.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Pending`    | pending      |
| `Picking`    | picking      |
| `Prepared`   | prepared     |
| `HandedOver` | handed_over  |
| `InTransit`  | in_transit   |
| `Delivered`  | delivered    |
| `Failed`     | failed       |