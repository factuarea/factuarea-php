# CalculateTotalsRequest

Calculate the totals of a set of lines. Body: `lines[]`, each with `quantity`, `unit_price` and the optional `discount`, `vat_rate`, `retention_rate` and `surcharge_rate`. Returns the subtotal, VAT, surcharge, withholding and total. `price` is accepted as a legacy alias of `unit_price`.


## Fields

| Field                                                                                                 | Type                                                                                                  | Required                                                                                              | Description                                                                                           |
| ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `lines`                                                                                               | array<[Components\CalculateTotalsRequestLine](../../Models/Components/CalculateTotalsRequestLine.md)> | :heavy_check_mark:                                                                                    | N/A                                                                                                   |