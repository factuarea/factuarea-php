# CalculateTotalsRequest

Public REST API v1 — POST /v1/taxes/calculate-totals.

Body: `{ lines: [{ quantity, unit_price, discount?, vat_rate?,
retention_rate?, surcharge_rate? }] }`. Returns subtotal, VAT, surcharge,
withholding and total.

The canonical field is `unit_price` (aligned with Invoice/Quote lines).
`price` is accepted as a legacy alias so as not to break integrators that
already send the previous shape; the controller normalizes it to `unit_price`.


## Fields

| Field                                                                                                 | Type                                                                                                  | Required                                                                                              | Description                                                                                           |
| ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `lines`                                                                                               | array<[Components\CalculateTotalsRequestLine](../../Models/Components/CalculateTotalsRequestLine.md)> | :heavy_check_mark:                                                                                    | N/A                                                                                                   |