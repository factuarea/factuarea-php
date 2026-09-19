# PublicApiV1StockReservationsListSort

Sort order. Use a field for ascending or a `-` prefix for descending (e.g. `-created`). Allowed fields: `held_at`, `expires_at`. Combined with the cursor, ordering stays deterministic (a stable secondary sort by the cursor id, Stripe-style). When omitted, results follow the default cursor order (`held_at` descending, the most recently held first).


## Values

| Name             | Value            |
| ---------------- | ---------------- |
| `HeldAt`         | held_at          |
| `MinusHeldAt`    | -held_at         |
| `ExpiresAt`      | expires_at       |
| `MinusExpiresAt` | -expires_at      |