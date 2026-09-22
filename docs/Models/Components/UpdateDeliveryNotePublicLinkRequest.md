# UpdateDeliveryNotePublicLinkRequest

Public REST API v1 — PATCH /v1/companies/{company}/delivery-notes/{delivery_note}/public-link.

Required body: `action` ∈ {revoke, activate, extend, reset}. `extend_days`
is required when `action=extend`.


## Fields

| Field                                                                                                                        | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `action`                                                                                                                     | [Components\UpdateDeliveryNotePublicLinkRequestAction](../../Models/Components/UpdateDeliveryNotePublicLinkRequestAction.md) | :heavy_check_mark:                                                                                                           | N/A                                                                                                                          |
| `extendDays`                                                                                                                 | *?int*                                                                                                                       | :heavy_minus_sign:                                                                                                           | N/A                                                                                                                          |