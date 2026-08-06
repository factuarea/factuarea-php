# EventDataDeliveryNoteEmailSent

Payload (`data`) emitted with the `delivery_note.email_sent` event: the full resource snapshot captured at emission time under `object`, plus event-specific keys.


## Fields

| Field                                                              | Type                                                               | Required                                                           | Description                                                        |
| ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ | ------------------------------------------------------------------ |
| `type`                                                             | *string*                                                           | :heavy_check_mark:                                                 | N/A                                                                |
| `object`                                                           | [Components\DeliveryNote](../../Models/Components/DeliveryNote.md) | :heavy_check_mark:                                                 | A delivery note tracking goods delivered to a customer.            |
| `recipientEmail`                                                   | *string*                                                           | :heavy_check_mark:                                                 | N/A                                                                |
| `deliveryNoteNumber`                                               | *string*                                                           | :heavy_check_mark:                                                 | N/A                                                                |
| `cc`                                                               | array<*string*>                                                    | :heavy_check_mark:                                                 | N/A                                                                |
| `bcc`                                                              | array<*string*>                                                    | :heavy_check_mark:                                                 | N/A                                                                |