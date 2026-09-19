# PublicApiV1PurchaseOrdersListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `created`, `number`, `expected_date`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `Created`           | created             |
| `MinusCreated`      | -created            |
| `Number`            | number              |
| `MinusNumber`       | -number             |
| `ExpectedDate`      | expected_date       |
| `MinusExpectedDate` | -expected_date      |