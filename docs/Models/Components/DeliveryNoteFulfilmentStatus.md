# DeliveryNoteFulfilmentStatus

Status of the PHYSICAL axis of the delivery note — where the goods are — which is ORTHOGONAL to the `status` above: no transition of this axis changes the commercial status and no commercial transition changes this one. It is NEVER `null`: its column is `NOT NULL DEFAULT pending`, so a note that never entered picking publishes the initial status of the catalog. Its closed catalog is published by its own operation (`GET .../delivery-notes/fulfilment-statuses`) and shares no value with the catalog of delivery note statuses.


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