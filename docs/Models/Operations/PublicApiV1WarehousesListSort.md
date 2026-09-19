# PublicApiV1WarehousesListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `created`, `code`, `name`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Created`      | created        |
| `MinusCreated` | -created       |
| `Code`         | code           |
| `MinusCode`    | -code          |
| `Name`         | name           |
| `MinusName`    | -name          |