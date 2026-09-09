# PublicApiV1RecurringInvoicesPreviewResponseBody

Upcoming dates. When expand=document is supplied, next_invoice includes the computed lines and totals without persisting an invoice.


## Fields

| Field                                                                                                     | Type                                                                                                      | Required                                                                                                  | Description                                                                                               |
| --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| `data`                                                                                                    | array<[Components\RecurringInvoicePreviewDate](../../Models/Components/RecurringInvoicePreviewDate.md)>   | :heavy_check_mark:                                                                                        | N/A                                                                                                       |
| `nextInvoice`                                                                                             | [?Components\RecurringInvoicePreviewDocument](../../Models/Components/RecurringInvoicePreviewDocument.md) | :heavy_minus_sign:                                                                                        | N/A                                                                                                       |