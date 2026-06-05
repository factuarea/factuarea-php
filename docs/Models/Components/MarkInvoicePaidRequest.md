# MarkInvoicePaidRequest

Public REST API v1 — POST /v1/invoices/{uuid}/mark_paid.

Body opcional: `paid_on` (date, default hoy), `payment_method`,
`notes`.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `paidOn`                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |
| `paymentMethod`                                               | *?string*                                                     | :heavy_minus_sign:                                            | N/A                                                           |
| `notes`                                                       | *?string*                                                     | :heavy_minus_sign:                                            | N/A                                                           |