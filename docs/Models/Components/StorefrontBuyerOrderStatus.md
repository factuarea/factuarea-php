# StorefrontBuyerOrderStatus

Lifecycle status of the ORDER. It is the same axis the merchant sees, published as a token so the shop window can tell the buyer where their order is without inventing a vocabulary of its own. A cancelled order is not served through this window at all.


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