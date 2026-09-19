# PublicApiV1StorefrontProductsListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `name`, `sku`, `created`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`created` descending).


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Name`         | name           |
| `MinusName`    | -name          |
| `Sku`          | sku            |
| `MinusSku`     | -sku           |
| `Created`      | created        |
| `MinusCreated` | -created       |