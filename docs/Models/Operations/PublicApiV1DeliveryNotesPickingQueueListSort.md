# PublicApiV1DeliveryNotesPickingQueueListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `delivery_date`, `expected_delivery_date`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name                        | Value                       |
| --------------------------- | --------------------------- |
| `DeliveryDate`              | delivery_date               |
| `MinusDeliveryDate`         | -delivery_date              |
| `ExpectedDeliveryDate`      | expected_delivery_date      |
| `MinusExpectedDeliveryDate` | -expected_delivery_date     |