# FindProductBySkuRequest

Public REST API v1 — POST /v1/companies/{company}/products/find-by-sku.

Looks up a product by its `sku` within the authenticated company. The `sku`
travels in the body (not in the URL) so as not to expose SKUs in logs/URLs
and because a SKU may contain valid `.` and `/` characters that would break
the path.


## Fields

| Field                                                                                                                                                       | Type                                                                                                                                                        | Required                                                                                                                                                    | Description                                                                                                                                                 |
| ----------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `sku`                                                                                                                                                       | *string*                                                                                                                                                    | :heavy_check_mark:                                                                                                                                          | N/A                                                                                                                                                         |
| `date`                                                                                                                                                      | *?string*                                                                                                                                                   | :heavy_minus_sign:                                                                                                                                          | Day (`YYYY-MM-DD`, company time zone) the `promotion` is resolved for; default today. A date-time uses its day.<br/>Malformed: 400 `parameter_invalid_iso8601`. |