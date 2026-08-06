# RecurringInvoiceAutoDelivery

Rich auto-delivery configuration (superset of the scalar `email_to`): the generated invoices are emailed to `recipients` (with optional `cc`) using `subject`/`body`. `recipients`/`cc` are empty lists and `subject`/`body` are `null` when nothing is configured.


## Fields

| Field                                                                       | Type                                                                        | Required                                                                    | Description                                                                 |
| --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| `sendAutomatically`                                                         | *bool*                                                                      | :heavy_check_mark:                                                          | If `true`, generated invoices are automatically emailed to `recipients`.    |
| `recipients`                                                                | array<*string*>                                                             | :heavy_check_mark:                                                          | Primary recipients of the automatic dispatch. Empty list if not configured. |
| `cc`                                                                        | array<*string*>                                                             | :heavy_check_mark:                                                          | Carbon-copy recipients. Empty list if not configured.                       |
| `subject`                                                                   | *string*                                                                    | :heavy_check_mark:                                                          | Custom email subject. `null` to use the default.                            |
| `body`                                                                      | *string*                                                                    | :heavy_check_mark:                                                          | Custom email body. `null` to use the default.                               |