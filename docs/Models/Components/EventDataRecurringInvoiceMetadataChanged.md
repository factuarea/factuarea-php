# EventDataRecurringInvoiceMetadataChanged

Payload (`data`) emitted with the `recurring_invoice.metadata_changed` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                                         | Type                                                                          | Required                                                                      | Description                                                                   |
| ----------------------------------------------------------------------------- | ----------------------------------------------------------------------------- | ----------------------------------------------------------------------------- | ----------------------------------------------------------------------------- |
| `type`                                                                        | *string*                                                                      | :heavy_check_mark:                                                            | N/A                                                                           |
| `object`                                                                      | [Components\RecurringInvoice](../../Models/Components/RecurringInvoice.md)    | :heavy_check_mark:                                                            | A recurring invoice template that auto-generates invoices on a fixed cadence. |
| `previousKeys`                                                                | array<*string*>                                                               | :heavy_check_mark:                                                            | N/A                                                                           |
| `newKeys`                                                                     | array<*string*>                                                               | :heavy_check_mark:                                                            | N/A                                                                           |