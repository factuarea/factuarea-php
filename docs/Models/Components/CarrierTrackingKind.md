# CarrierTrackingKind

How the tracking of this carrier is resolved: `url_template` (we build the tracking URL out of `tracking_url_template` and the tracking number of the shipment), `manual` (the number is recorded but there is no address to send the customer to) and `store_provided` (the online store supplies the link). Only `url_template` carries a template, which is why the template key is optional.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `UrlTemplate`   | url_template    |
| `Manual`        | manual          |
| `StoreProvided` | store_provided  |