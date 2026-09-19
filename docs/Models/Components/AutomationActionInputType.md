# AutomationActionInputType

Kind of action to run. Must be one of the action types `GET /v1/automations/catalog` reports as registered.


## Values

| Name                            | Value                           |
| ------------------------------- | ------------------------------- |
| `NotifyInApp`                   | notify_in_app                   |
| `NotifyChannel`                 | notify_channel                  |
| `EmitWebhook`                   | emit_webhook                    |
| `CreateCalendarEvent`           | create_calendar_event           |
| `SendDocumentEmail`             | send_document_email             |
| `SendPaymentReminder`           | send_payment_reminder           |
| `ChangeStatus`                  | change_status                   |
| `TagEntity`                     | tag_entity                      |
| `ReserveStock`                  | reserve_stock                   |
| `ReleaseStockReservation`       | release_stock_reservation       |
| `CreatePurchaseOrder`           | create_purchase_order           |
| `CreateDeliveryNoteFromOrder`   | create_delivery_note_from_order |
| `PublishCatalogToStore`         | publish_catalog_to_store        |
| `PushPriceToStore`              | push_price_to_store             |
| `PushStockToStore`              | push_stock_to_store             |
| `ApplyPriceList`                | apply_price_list                |