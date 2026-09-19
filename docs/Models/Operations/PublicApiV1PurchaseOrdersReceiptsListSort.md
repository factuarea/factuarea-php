# PublicApiV1PurchaseOrdersReceiptsListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `received_on`, `number`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name              | Value             |
| ----------------- | ----------------- |
| `ReceivedOn`      | received_on       |
| `MinusReceivedOn` | -received_on      |
| `Number`          | number            |
| `MinusNumber`     | -number           |