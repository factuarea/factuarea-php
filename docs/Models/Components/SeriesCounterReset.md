# SeriesCounterReset

Counter reset policy (source of truth): `never` (the counter never resets), `annual` (resets on January 1st) or `monthly` (resets on the 1st of each month). `monthly` requires the `number_format` mask to include the `{MM}` token to keep rendered numbers unique across months.


## Values

| Name      | Value     |
| --------- | --------- |
| `Never`   | never     |
| `Annual`  | annual    |
| `Monthly` | monthly   |