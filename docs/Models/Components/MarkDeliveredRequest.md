# MarkDeliveredRequest

Mark a delivery note as delivered (`draft` → `delivered`). Optional body: `delivery_date` (ISO 8601 `YYYY-MM-DD`). If omitted, the delivery date already recorded is used and, failing that, the current date.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `deliveryDate`                                                | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_minus_sign:                                            | N/A                                                           |