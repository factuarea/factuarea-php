# EndedManagedAccountManagement

End of the management by your account.


## Fields

| Field                                                                                        | Type                                                                                         | Required                                                                                     | Description                                                                                  | Example                                                                                      |
| -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------- |
| `status`                                                                                     | [Components\EndedManagedAccountStatus](../../Models/Components/EndedManagedAccountStatus.md) | :heavy_check_mark:                                                                           | Always `ended`.                                                                              | ended                                                                                        |
| `endedAt`                                                                                    | [\DateTime](https://www.php.net/manual/en/class.datetime.php)                                | :heavy_check_mark:                                                                           | When the management ended (ISO 8601).                                                        | 2026-10-05T10:00:00+00:00                                                                    |