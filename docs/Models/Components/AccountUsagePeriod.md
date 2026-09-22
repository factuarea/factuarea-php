# AccountUsagePeriod

Boundaries of the period being reported.


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   | Example                                                       |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `start`                                                       | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | First day of the period, inclusive (ISO 8601 date).           | 2026-09-01                                                    |
| `end`                                                         | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Last day of the period, inclusive (ISO 8601 date).            | 2026-09-30                                                    |