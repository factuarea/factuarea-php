# PublicApiV1DeliveryNotesPickingQueueListFulfilmentStatus

State of the PHYSICAL axis of the delivery note: `pending` (nothing prepared yet), `picking` (being prepared), `prepared` (ready to hand over), `handed_over` (the goods are with the carrier), `in_transit`, `delivered` (the recipient has them) and `failed`. It is ORTHOGONAL to the commercial status of the document, which this queue does not filter by: the queue answers where the goods are, not where the paperwork is. Ask for the work still to do with the list form, for example the two states before the goods leave. Exact match on `fulfilment_status`.


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