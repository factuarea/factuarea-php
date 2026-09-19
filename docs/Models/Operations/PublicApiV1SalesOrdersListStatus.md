# PublicApiV1SalesOrdersListStatus

Sales order status. Eight published values: `draft` (created, not numbered yet), `confirmed` (numbered and committed), `partially_served`, `served`, `partially_invoiced`, `invoiced`, `closed` and `cancelled`. The four consumption values — `partially_served`, `served`, `partially_invoiced` and `invoiced` — are DERIVED from the quantities that delivery notes and invoices take from the order lines, so they are never a transition target: the only statuses you can ask an order to move to are `confirmed`, `cancelled` and `closed`, and `draft` is left behind by the confirmation, which stamps a series number. Exact match on `status`.


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `Draft`             | draft               |
| `Confirmed`         | confirmed           |
| `PartiallyServed`   | partially_served    |
| `Served`            | served              |
| `PartiallyInvoiced` | partially_invoiced  |
| `Invoiced`          | invoiced            |
| `Closed`            | closed              |
| `Cancelled`         | cancelled           |