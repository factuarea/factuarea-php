# UpdateProductStockRequestOperation

How `stock` applies: `set` replaces the balance (default), `increase` adds, `decrease` subtracts. `add`/`subtract` are accepted aliases. A manual decrease below zero fails with 422.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Set`      | set        |
| `Increase` | increase   |
| `Decrease` | decrease   |
| `Add`      | add        |
| `Subtract` | subtract   |