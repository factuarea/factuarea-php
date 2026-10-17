# LastInvoice

The last issued invoice of the company regardless of the period, or `null` when it has not issued any yet.


## Fields

| Field                                                                 | Type                                                                  | Required                                                              | Description                                                           | Example                                                               |
| --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- | --------------------------------------------------------------------- |
| `number`                                                              | *string*                                                              | :heavy_check_mark:                                                    | Invoice number.                                                       | F-2026-003                                                            |
| `issuedOn`                                                            | [\DateTime](https://www.php.net/manual/en/class.datetime.php)         | :heavy_check_mark:                                                    | Issue date (ISO 8601 date), the same name as in the invoice resource. | 2026-09-20                                                            |
| `total`                                                               | *string*                                                              | :heavy_check_mark:                                                    | Invoice total in euros as a 2-decimal string.                         | 121.00                                                                |