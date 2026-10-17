# FindInvoiceByNumberRequest

Public REST API v1 — POST /v1/companies/{company}/invoices/find-by-number.

Body: `number` (invoice number, required) plus TWO optional discriminators,
because a number repeats for two independent reasons:

- `year` — series recycle numbering across fiscal years.
- `series_id` — two series of the same company each issue their own
  `F-2026-001`; the year cannot tell them apart. Accepts the public UUID v7
  of a series of type `invoice` belonging to the caller's company.

Aligned with Supplier's `find-by-tax-id`.


## Fields

| Field                                                                                                             | Type                                                                                                              | Required                                                                                                          | Description                                                                                                       |
| ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `number`                                                                                                          | *string*                                                                                                          | :heavy_check_mark:                                                                                                | N/A                                                                                                               |
| `year`                                                                                                            | *?int*                                                                                                            | :heavy_minus_sign:                                                                                                | N/A                                                                                                               |
| `seriesId`                                                                                                        | *?string*                                                                                                         | :heavy_minus_sign:                                                                                                | Public identifier (UUID v7) of the series that issued the invoice; it tells apart two series that share a number. |