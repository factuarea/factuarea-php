# PurchaseOrderStatus

Lifecycle status of the order: `draft` (editable), `sent` (issued to the supplier), `confirmed` (accepted by the supplier), `partially_received` and `received` (goods arrived in part or in full), `billed` (the supplier invoice matched it) and `cancelled`. The two reception states are DERIVED from posting a goods receipt and are not destinations you can ask for, and `billed` is stamped by the three-way match from the supplier invoice. Both `billed` and `cancelled` are terminal and no operation reopens them.


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `Draft`             | draft               |
| `Sent`              | sent                |
| `Confirmed`         | confirmed           |
| `PartiallyReceived` | partially_received  |
| `Received`          | received            |
| `Billed`            | billed              |
| `Cancelled`         | cancelled           |