# PublicApiV1QuotesListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `created`, `total`, `number`, `valid_until`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name              | Value             |
| ----------------- | ----------------- |
| `Created`         | created           |
| `MinusCreated`    | -created          |
| `Total`           | total             |
| `MinusTotal`      | -total            |
| `Number`          | number            |
| `MinusNumber`     | -number           |
| `ValidUntil`      | valid_until       |
| `MinusValidUntil` | -valid_until      |