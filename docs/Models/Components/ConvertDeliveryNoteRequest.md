# ConvertDeliveryNoteRequest

Public REST API v1 — POST /v1/delivery_notes/{uuid}/convert.

Required body: `target` ∈ {invoice, proforma}. The spec lists both
targets, but the DeliveryNote BC in this phase only supports
conversion to invoice — the controller returns 422 with a descriptive
message if `target=proforma` until the BC adds the
`ConvertDeliveryNoteToProformaCommand`.


## Fields

| Field                                                                                                      | Type                                                                                                       | Required                                                                                                   | Description                                                                                                |
| ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------- |
| `target`                                                                                                   | [Components\ConvertDeliveryNoteRequestTarget](../../Models/Components/ConvertDeliveryNoteRequestTarget.md) | :heavy_check_mark:                                                                                         | N/A                                                                                                        |