# PublicApiV1CompaniesSeatChargePreviewResponseBody

Preview the charge of the additional tax IDs (seats) of the account without creating a company or charging a payment method. `amount`, `tax_amount` and `total` are euro 2-decimal strings; `recurring_base_cents` and `recurring_total_cents` stay in cents, as their names state.


## Fields

| Field                                                                                      | Type                                                                                       | Required                                                                                   | Description                                                                                |
| ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ |
| `data`                                                                                     | [Components\CompanySeatChargePreview](../../Models/Components/CompanySeatChargePreview.md) | :heavy_check_mark:                                                                         | N/A                                                                                        |