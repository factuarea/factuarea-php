# PublicApiV1PurchaseInvoicesListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `created`, `total`, `issued_on`, `due_on`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name            | Value           |
| --------------- | --------------- |
| `Created`       | created         |
| `MinusCreated`  | -created        |
| `Total`         | total           |
| `MinusTotal`    | -total          |
| `IssuedOn`      | issued_on       |
| `MinusIssuedOn` | -issued_on      |
| `DueOn`         | due_on          |
| `MinusDueOn`    | -due_on         |