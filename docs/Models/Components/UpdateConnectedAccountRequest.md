# UpdateConnectedAccountRequest

Update a connected Stripe account (multi-store). All fields are optional with merge semantics (an omitted field is left unchanged): `name`, `series_id` (auto-invoicing series UUID; `null` clears it back to the company default), `simplified_threshold_cents` (0-300000), `autoinvoicing_enabled`, `require_nif`, `refunds_enabled`, `subscription_autoinvoicing_enabled` and `invoice_note` (up to 500 characters, written in the notes of the auto-created invoices; `null` clears it).


## Fields

| Field                              | Type                               | Required                           | Description                        |
| ---------------------------------- | ---------------------------------- | ---------------------------------- | ---------------------------------- |
| `name`                             | *?string*                          | :heavy_minus_sign:                 | N/A                                |
| `seriesId`                         | *?string*                          | :heavy_minus_sign:                 | N/A                                |
| `simplifiedThresholdCents`         | *?int*                             | :heavy_minus_sign:                 | N/A                                |
| `autoinvoicingEnabled`             | *?bool*                            | :heavy_minus_sign:                 | N/A                                |
| `requireNif`                       | *?bool*                            | :heavy_minus_sign:                 | N/A                                |
| `refundsEnabled`                   | *?bool*                            | :heavy_minus_sign:                 | N/A                                |
| `subscriptionAutoinvoicingEnabled` | *?bool*                            | :heavy_minus_sign:                 | N/A                                |
| `defaultLineConcept`               | *?string*                          | :heavy_minus_sign:                 | N/A                                |
| `invoiceNote`                      | *?string*                          | :heavy_minus_sign:                 | N/A                                |