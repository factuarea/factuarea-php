# UpdateConnectedAccountRequestCrossBorderSupply

Kind of operation this account sells to customers outside Spain: `services` (the default) or `goods`. It decides how an invoice line without tax charged to such a customer is qualified. Omitted leaves it unchanged. A value outside the catalog is rejected with 422 `parameter_invalid_value` and `param` `cross_border_supply`, and the stored value does not change.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Services` | services   |
| `Goods`    | goods      |