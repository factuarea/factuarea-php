# Composition

Informative breakdown of the payout reported by Stripe (component charges and fees). Component references are opaque Stripe ids (`payment_intent` = `pi_xxx`, `charge_id` = `ch_xxx`), NOT internal payment UUIDs. May be empty when the breakdown could not be read.


## Fields

| Field                                                               | Type                                                                | Required                                                            | Description                                                         | Example                                                             |
| ------------------------------------------------------------------- | ------------------------------------------------------------------- | ------------------------------------------------------------------- | ------------------------------------------------------------------- | ------------------------------------------------------------------- |
| `components`                                                        | array<[Components\Component](../../Models/Components/Component.md)> | :heavy_minus_sign:                                                  | N/A                                                                 |                                                                     |
| `feeTotal`                                                          | *?float*                                                            | :heavy_minus_sign:                                                  | N/A                                                                 | 45.44                                                               |
| `grossTotal`                                                        | *?float*                                                            | :heavy_minus_sign:                                                  | N/A                                                                 | 1280                                                                |