# UpdateCarrierRequestTrackingKind

Where the tracking link of a shipment comes from: `url_template`, `manual` or `store_provided`. Declaring `url_template` and clearing the address in the SAME request is rejected, because it would leave a carrier that promises a link it cannot build; moving to that kind without touching the address is accepted when the carrier already has one.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `UrlTemplate`   | url_template    |
| `Manual`        | manual          |
| `StoreProvided` | store_provided  |