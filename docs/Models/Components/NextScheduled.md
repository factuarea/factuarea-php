# NextScheduled


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `id`                                                          | *string*                                                      | :heavy_check_mark:                                            | Public identifier (UUID v7) of the recurring invoice.         |
| `name`                                                        | *string*                                                      | :heavy_check_mark:                                            | Name of the recurrence.                                       |
| `nextRunDate`                                                 | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Date of the next run (ISO 8601).                              |