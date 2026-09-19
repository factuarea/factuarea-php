# BulkUpdateProductStockRequest

Public REST API v1 — POST /v1/products/bulk-update-stock.

Body: `{ updates: [{ product_id: string, stock: numeric-string, operation?: 'set'|'add'|'subtract', variant_id?: uuid, warehouse_id?: uuid }] }`.
Accepts up to 500 updates in a single operation. `variant_id` targets the
own balance of a variant of that product; a variant that does not belong to
the product is skipped like an unknown product (skip-on-miss). `warehouse_id`
books that entry to one specific warehouse and follows the SAME
skip-on-miss contract; absent → the chain resolves it (product default,
then company default).

We accept `items` as an alias of the canonical `updates` field for
forgiveness with integrators following the most common convention. The
controller normalizes it to `updates`.


## Fields

| Field                                                                                                                                                                                                                            | Type                                                                                                                                                                                                                             | Required                                                                                                                                                                                                                         | Description                                                                                                                                                                                                                      |
| -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `updates`                                                                                                                                                                                                                        | array<[Components\Update](../../Models/Components/Update.md)>                                                                                                                                                                    | :heavy_check_mark:                                                                                                                                                                                                               | Up to 500 entries `{product_id, stock, operation?, variant_id?, warehouse_id?}`. `variant_id` targets the own balance of a variant of that product; entries whose product, variant or warehouse is unknown are skipped silently. |