# ChangeDeliveryNoteStatusRequest

Public REST API v1 — POST /v1/delivery_notes/{uuid}/change_status.

Required body: `status` ∈ {sent, delivered, cancelled}. The controller
maps invalid transitions of the DeliveryNote BC to 422
`invalid_status_transition`.

Note: the internal BC only models `draft|delivered|invoiced|cancelled`.
The public status `sent` is treated as a synonym of `delivered`
(transition `draft → delivered` when there is no signature yet; the
public status `signed` is derived from the presence of a signature +
internal status `delivered`).


## Fields

| Field                                                                                                                | Type                                                                                                                 | Required                                                                                                             | Description                                                                                                          |
| -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| `status`                                                                                                             | [Components\ChangeDeliveryNoteStatusRequestStatus](../../Models/Components/ChangeDeliveryNoteStatusRequestStatus.md) | :heavy_check_mark:                                                                                                   | N/A                                                                                                                  |