# VerifactuStatus

`registered`: the alta exists. `failed`: the invoice is issued but the alta could not be generated — `error_code` says why and a retry with the same `external_id` completes it.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `Registered` | registered   |
| `Failed`     | failed       |