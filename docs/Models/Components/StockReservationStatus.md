# StockReservationStatus

Lifecycle status: `held` (holding units), `released` (given back) and `consumed` (turned into a real stock movement when the order was served). The two last ones are TERMINAL: nothing holds again what was released.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Held`     | held       |
| `Released` | released   |
| `Consumed` | consumed   |