# SimplifiedInvoiceEligibilityV1Request

Public REST API v1 — POST /v1/invoices/simplified-eligibility.

Checks whether an invoice with a given `total` amount (and, optionally,
client data) can be issued as simplified (F2) or must be issued as a
full invoice (F1) under Royal Decree 1619/2012 (art. 4).


## Fields

| Field                      | Type                       | Required                   | Description                |
| -------------------------- | -------------------------- | -------------------------- | -------------------------- |
| `total`                    | *float*                    | :heavy_check_mark:         | N/A                        |
| `clientId`                 | *?string*                  | :heavy_minus_sign:         | N/A                        |
| `clientCountry`            | *?string*                  | :heavy_minus_sign:         | N/A                        |
| `isIntraCommunity`         | *?bool*                    | :heavy_minus_sign:         | N/A                        |
| `isReverseCharge`          | *?bool*                    | :heavy_minus_sign:         | N/A                        |
| `clientRequiresDeductible` | *?bool*                    | :heavy_minus_sign:         | N/A                        |