# UpdateStripeAutoinvoicingConfigRequest

Update the Stripe auto-invoicing configuration. `enabled` toggles auto-invoicing; `series_id` (UUID v7, nullable) sets the series for auto-created invoices (`null` uses the company default); `simplified_threshold_cents` (0-300000), `require_nif`, `refunds_enabled` and `subscription_autoinvoicing_enabled` are optional partial fields.


## Fields

| Field                              | Type                               | Required                           | Description                        |
| ---------------------------------- | ---------------------------------- | ---------------------------------- | ---------------------------------- |
| `enabled`                          | *bool*                             | :heavy_check_mark:                 | N/A                                |
| `seriesId`                         | *?string*                          | :heavy_minus_sign:                 | N/A                                |
| `simplifiedThresholdCents`         | *?int*                             | :heavy_minus_sign:                 | N/A                                |
| `requireNif`                       | *?bool*                            | :heavy_minus_sign:                 | N/A                                |
| `refundsEnabled`                   | *?bool*                            | :heavy_minus_sign:                 | N/A                                |
| `subscriptionAutoinvoicingEnabled` | *?bool*                            | :heavy_minus_sign:                 | N/A                                |