# SalesOrderChannel

Origin the order came in through: `manual` (entered by hand), `store` (connected shop), `storefront` (custom web shop), `api` (this public API), `quote` or `proforma` (converted from one of them) and `assistant` (conversational assistant). It is set on creation and no operation accepts it afterwards: the origin of an order is history, not a field.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Manual`     | manual       |
| `Store`      | store        |
| `Storefront` | storefront   |
| `Api`        | api          |
| `Quote`      | quote        |
| `Proforma`   | proforma     |
| `Assistant`  | assistant    |