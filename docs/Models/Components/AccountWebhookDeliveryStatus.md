# AccountWebhookDeliveryStatus

Delivery status. `pending` (queued or waiting for its next attempt; in the `test` environment a delivery is recorded but never sent, so it stays `pending`), `delivered` (a 2xx was received), `failed` (a recoverable failure), `failed_permanently` (attempts exhausted or the destination refused it; it can be retried by hand) and `cancelled` (aborted, terminal; see `cancel_reason`).


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `Pending`           | pending             |
| `Delivered`         | delivered           |
| `Failed`            | failed              |
| `FailedPermanently` | failed_permanently  |
| `Cancelled`         | cancelled           |