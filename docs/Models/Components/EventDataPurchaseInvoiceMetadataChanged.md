# EventDataPurchaseInvoiceMetadataChanged

Payload (`data`) emitted with the `purchase_invoice.metadata_changed` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                                    | Type                                                                     | Required                                                                 | Description                                                              |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ | ------------------------------------------------------------------------ |
| `type`                                                                   | *string*                                                                 | :heavy_check_mark:                                                       | N/A                                                                      |
| `object`                                                                 | [Components\PurchaseInvoice](../../Models/Components/PurchaseInvoice.md) | :heavy_check_mark:                                                       | An invoice received from a supplier.                                     |