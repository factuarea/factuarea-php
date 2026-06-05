# Addon

State of the `developer_api` addon for this company.


## Fields

| Field                                                                  | Type                                                                   | Required                                                               | Description                                                            | Example                                                                |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `active`                                                               | *bool*                                                                 | :heavy_check_mark:                                                     | true when the addon is currently usable (paid or within grace period). | true                                                                   |
| `inGrace`                                                              | *bool*                                                                 | :heavy_check_mark:                                                     | true when the subscription lapsed but the grace period is still open.  | false                                                                  |
| `expiresAt`                                                            | [\DateTime](https://www.php.net/manual/en/class.datetime.php)          | :heavy_check_mark:                                                     | Grace period cutoff (ISO 8601) when `in_grace=true`; null otherwise.   | 2026-08-24T14:15:22Z                                                   |