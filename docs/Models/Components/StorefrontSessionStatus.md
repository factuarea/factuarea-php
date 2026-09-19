# StorefrontSessionStatus

Status of the cart: `open` (it can still be changed), `converted` (it became an order and is now history) and `abandoned` (given up). Expiry is NOT here: an expired cart stays `open` and publishes it through its own flag, because "it ran out of time" and "the buyer gave up" are different facts with different remedies.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `Open`      | open        |
| `Converted` | converted   |
| `Abandoned` | abandoned   |