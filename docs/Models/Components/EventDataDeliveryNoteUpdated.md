# EventDataDeliveryNoteUpdated

Payload (`data`) emitted with the `delivery_note.updated` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                              | Type                                                               | Required                                                           | Description                                                        |
| ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ |
| `type`                                                             | *string*                                                           | :heavy_check_mark:                                                 | N/A                                                                |
| `object`                                                           | [Components\DeliveryNote](../../Models/Components/DeliveryNote.md) | :heavy_check_mark:                                                 | A delivery note tracking goods delivered to a customer.            |