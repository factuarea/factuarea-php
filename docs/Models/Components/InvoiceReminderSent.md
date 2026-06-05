# InvoiceReminderSent

Acknowledgement of sending a payment reminder.


## Fields

| Field                                              | Type                                               | Required                                           | Description                                        | Example                                            |
| -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- | -------------------------------------------------- |
| `id`                                               | *string*                                           | :heavy_check_mark:                                 | UUID (v7) of the invoice the reminder was sent to. | feb47de0-d6ed-40de-a4d4-8825182f5a6d               |
| `message`                                          | *string*                                           | :heavy_check_mark:                                 | Confirmation message.                              | Recordatorio enviado correctamente.                |