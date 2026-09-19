# CreateCarrierRequestTrackingKind

Where the tracking link of a shipment comes from: `url_template` (you publish an address with a placeholder and each shipment fills it in with its own tracking number), `manual` (there is a tracking number but no address to build a link with, so no link is published) or `store_provided` (the link travels from the shop the order came from). It is a closed set and the value is taken literally: a different casing or a surrounding space is rejected rather than corrected, so the same kind is never stored two ways.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `UrlTemplate`   | url_template    |
| `Manual`        | manual          |
| `StoreProvided` | store_provided  |