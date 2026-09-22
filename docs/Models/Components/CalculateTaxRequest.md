# CalculateTaxRequest

Public REST API v1 — POST /v1/companies/{company}/taxes/calculate.

Body: `{ base: float, taxes_id: string }`. `taxes_id` es la FK a la tabla
global `taxes` (valor UUID v7) — plural (D1), NUNCA `tax_id` (NIF/CIF fiscal).
Devuelve `{ base, tax_rate, tax_amount, total_amount, tax }`.


## Fields

| Field                                                                                                                                        | Type                                                                                                                                         | Required                                                                                                                                     | Description                                                                                                                                  |
| -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------- |
| `base`                                                                                                                                       | *float*                                                                                                                                      | :heavy_check_mark:                                                                                                                           | N/A                                                                                                                                          |
| `taxesId`                                                                                                                                    | *string*                                                                                                                                     | :heavy_check_mark:                                                                                                                           | Identifier (UUID v7) of the tax to apply, from the global `taxes` catalog. A well-formed but non-existent value returns 404 `tax_not_found`. |