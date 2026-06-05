# ConvertProformaRequest

Public REST API v1 — POST /v1/proformas/{uuid}/convert.

Required body: `target` ∈ {invoice}. Only conversion to invoice is
supported — other targets (`proforma`, `delivery_note`) do NOT apply
because a proforma can only be converted to an invoice by BC design.


## Fields

| Field                                                                                              | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `target`                                                                                           | [Components\ConvertProformaRequestTarget](../../Models/Components/ConvertProformaRequestTarget.md) | :heavy_check_mark:                                                                                 | N/A                                                                                                |