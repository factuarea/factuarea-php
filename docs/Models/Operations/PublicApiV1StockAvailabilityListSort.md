# PublicApiV1StockAvailabilityListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `available_total`, `product_code`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (the public ID of the article, ascending).


## Values

| Name                  | Value                 |
| --------------------- | --------------------- |
| `AvailableTotal`      | available_total       |
| `MinusAvailableTotal` | -available_total      |
| `ProductCode`         | product_code          |
| `MinusProductCode`    | -product_code         |