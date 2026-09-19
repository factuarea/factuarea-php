# FulfilmentStatusItemValue

Status token of the physical axis. It is the identity of the status: a token of a closed catalog, not a row of any table. `pending` is where every delivery note starts, `delivered` is TERMINAL, and `failed` admits a second attempt back into `in_transit`.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Pending`    | pending      |
| `Picking`    | picking      |
| `Prepared`   | prepared     |
| `HandedOver` | handed_over  |
| `InTransit`  | in_transit   |
| `Delivered`  | delivered    |
| `Failed`     | failed       |