# PurchaseInvoiceMatchDeviationReason

Typed reason. Four compare a line against its counterpart on the order (`price_*` against the agreed cost per base unit, `quantity_*` against what was actually received); the other two exist because a line may have NO counterpart, which is a deviation just as real: `line_not_ordered` (the invoice brings an item the order does not contain) and `line_not_invoiced` (the order has a received line the invoice does not cover).


## Values

| Name              | Value             |
| ----------------- | ----------------- |
| `PriceAbove`      | price_above       |
| `PriceBelow`      | price_below       |
| `QuantityAbove`   | quantity_above    |
| `QuantityBelow`   | quantity_below    |
| `LineNotOrdered`  | line_not_ordered  |
| `LineNotInvoiced` | line_not_invoiced |