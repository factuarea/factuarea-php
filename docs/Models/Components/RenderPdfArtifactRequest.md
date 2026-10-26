# RenderPdfArtifactRequest

Render only the exact prepared artifact in `sync` or `async` mode, using its retained plan, source authority and admission marker. The server reuses the marker and canonical result, so an internal retry, idempotency replay or already READY artifact is not charged twice. Rendering never rereads live defaults, replaces the capture, publishes a new head or starts work from a metadata GET. A changed source or stale CAS requires refresh and an explicit new action.


## Fields

| Field                                                                                              | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `mode`                                                                                             | [Components\RenderPdfArtifactRequestMode](../../Models/Components/RenderPdfArtifactRequestMode.md) | :heavy_check_mark:                                                                                 | N/A                                                                                                |