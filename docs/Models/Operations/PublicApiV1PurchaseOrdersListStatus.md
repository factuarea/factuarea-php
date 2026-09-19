# PublicApiV1PurchaseOrdersListStatus

Purchase order status. Seven published values: `draft` (editable), `sent` (the supplier has the document), `confirmed` (the supplier accepted it), `partially_received`, `received`, `billed` and `cancelled`. The two reception values are DERIVED from posted goods receipts and `billed` from the supplier-invoice match, so none of the three is ever a transition target. Exact match on `status`.


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