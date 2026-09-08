# InvoiceLinePriceSemantics

Monetary semantics of the resolved price: `per_base_unit` (product/variant own price, converted once by the presentation factor) or `per_commercial_unit` (presentation, combination or price-list entry, never converted). `null` on a line with no catalog price context.


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `PerBaseUnit`       | per_base_unit       |
| `PerCommercialUnit` | per_commercial_unit |