# UpdateDeliveryNotePublicLinkRequest

Public REST API v1 — PUT /v1/delivery_notes/{uuid}/public-link.

Required body: `action` ∈ {revoke, activate, extend, reset}. `extend_days`
is required when `action=extend`.


## Fields

| Field                                                                                                                        | Type                                                                                                                         | Required                                                                                                                     | Description                                                                                                                  |
| ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| `action`                                                                                                                     | [Components\UpdateDeliveryNotePublicLinkRequestAction](../../Models/Components/UpdateDeliveryNotePublicLinkRequestAction.md) | :heavy_check_mark:                                                                                                           | N/A                                                                                                                          |
| `extendDays`                                                                                                                 | *?int*                                                                                                                       | :heavy_minus_sign:                                                                                                           | N/A                                                                                                                          |