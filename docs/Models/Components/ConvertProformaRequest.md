# ConvertProformaRequest

Convert a proforma. Required body: `target`, whose only accepted value is `invoice`: a proforma can only be converted to an invoice, so the other targets (`proforma`, `delivery_note`) do not apply.


## Fields

| Field                                                                                              | Type                                                                                               | Required                                                                                           | Description                                                                                        |
| -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| `target`                                                                                           | [Components\ConvertProformaRequestTarget](../../Models/Components/ConvertProformaRequestTarget.md) | :heavy_check_mark:                                                                                 | N/A                                                                                                |