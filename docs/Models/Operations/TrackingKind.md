# TrackingKind

Where the tracking link of a shipment comes from: `url_template` (the carrier publishes an address with a placeholder that each shipment fills in with its own tracking number), `manual` (there is a tracking number but no address to build a link with, so the link is not published) or `store_provided` (the link travels from the shop the order came from). Ask for two kinds at once with the list form. Exact match on `tracking_kind`.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `UrlTemplate`   | url_template    |
| `Manual`        | manual          |
| `StoreProvided` | store_provided  |