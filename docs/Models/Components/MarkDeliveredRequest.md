# MarkDeliveredRequest

Public REST API v1 — POST /v1/delivery_notes/{uuid}/mark-delivered.

REST sub-resource that transitions the delivery note `draft → delivered`. Optional
body: `delivery_date` (ISO 8601 `YYYY-MM-DD`). If omitted, the BC uses
the delivery date already recorded or, failing that, the current date.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `deliveryDate`                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |