# RegisterPurchaseInvoicePaymentRequest

Register a partial (or full) payment against an expense. Required: `amount` (> 0), `paid_on` (date) and `payment_method` (a value from the closed catalog). Optional: `bank_account_id`, `reference`, `notes`. The domain invariants (amount within the pending balance, issue date ≤ payment date ≤ today, invoice not cancelled) are enforced with a 422.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `amount`                                                      | *float*                                                       | :heavy_check_mark:                                            | N/A                                                           |
| `paidOn`                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | N/A                                                           |
| `paymentMethod`                                               | *string*                                                      | :heavy_check_mark:                                            | N/A                                                           |
| `bankAccountId`                                               | *?int*                                                        | :heavy_minus_sign:                                            | N/A                                                           |
| `reference`                                                   | *?string*                                                     | :heavy_minus_sign:                                            | N/A                                                           |
| `notes`                                                       | *?string*                                                     | :heavy_minus_sign:                                            | N/A                                                           |