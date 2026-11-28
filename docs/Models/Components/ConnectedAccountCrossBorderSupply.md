# ConnectedAccountCrossBorderSupply

Kind of operation this account sells to customers outside Spain: `services` or `goods`. It decides how an invoice line without tax charged to such a customer is qualified. Defaults to `services`. Editable with `PATCH /v1/companies/{company}/connected-accounts/{account}`.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Services` | services   |
| `Goods`    | goods      |