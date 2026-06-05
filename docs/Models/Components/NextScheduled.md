# NextScheduled


## Fields

| Field                                                         | Type                                                          | Required                                                      | Description                                                   |
| ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------- |
| `id`                                                          | *string*                                                      | :heavy_check_mark:                                            | UUID (v7) de la recurrencia.                                  |
| `name`                                                        | *string*                                                      | :heavy_check_mark:                                            | Nombre de la recurrencia.                                     |
| `nextRunDate`                                                 | [\DateTime](https://www.php.net/manual/en/class.datetime.php) | :heavy_check_mark:                                            | Date of the next run (ISO 8601).                              |