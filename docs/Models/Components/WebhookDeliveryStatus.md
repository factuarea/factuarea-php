# WebhookDeliveryStatus

Delivery status. `pending` (queued / in-flight), `delivered` (2xx received), `failed` (recoverable failure, will retry), `failed_permanently` (all attempts exhausted), `cancelled` (delivery aborted, terminal).


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `Pending`           | pending             |
| `Delivered`         | delivered           |
| `Failed`            | failed              |
| `FailedPermanently` | failed_permanently  |
| `Cancelled`         | cancelled           |