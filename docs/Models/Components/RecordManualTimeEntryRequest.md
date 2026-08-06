# RecordManualTimeEntryRequest


## Fields

| Field                                                          | Type                                                           | Required                                                       | Description                                                    |
| -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------- |
| `employeeId`                                                   | *string*                                                       | :heavy_check_mark:                                             | Employee ID (UUID v7) the retroactive workday is recorded for. |
| `startedAt`                                                    | [\DateTime](https://www.php.net/manual/en/class.datetime.php)  | :heavy_check_mark:                                             | Start of the retroactive workday in ISO 8601.                  |
| `endedAt`                                                      | [\DateTime](https://www.php.net/manual/en/class.datetime.php)  | :heavy_check_mark:                                             | End of the retroactive workday in ISO 8601 (after the start).  |
| `reason`                                                       | *string*                                                       | :heavy_check_mark:                                             | Required reason for the retroactive entry.                     |
| `pauses`                                                       | array<[Components\Pause](../../Models/Components/Pause.md)>    | :heavy_minus_sign:                                             | Optional breaks within the retroactive workday.                |