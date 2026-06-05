# InvoiceStatsPeriod

Period covered by the metrics.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `from`                                                        | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Start of the period (YYYY-MM-DD), or `null` if not filtered.  |
| `to`                                                          | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | End of the period (YYYY-MM-DD), or `null` if not filtered.    |