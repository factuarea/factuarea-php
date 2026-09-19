# SalesOrderStatus

Lifecycle status of the order: `draft` (editable, no number yet), `confirmed` (numbered and committed), `partially_served` and `served` (goods handed over in part or in full), `partially_invoiced` and `invoiced` (invoiced in part or in full), `closed` and `cancelled` (both terminal, neither reopens). The four consumption states are DERIVED from the quantities of the lines and are not destinations you can ask for: they move when a delivery note, an invoice or a return consumes the order, and they move BACK when such a consumption is reverted.


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