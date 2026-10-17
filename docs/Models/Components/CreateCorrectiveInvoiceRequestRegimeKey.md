# CreateCorrectiveInvoiceRequestRegimeKey

VeriFactu special-regime key of the corrected line (the closed AEAT catalog, e.g. `01` general regime, `14` or `15` for operations whose VAT accrues later than the issue date). OMIT it and the line inherits the key of the original line at the same index; SEND it (even `null`) and it replaces it, `null` meaning no special regime.


## Values

| Name        | Value       |
| ----------- | ----------- |
| `One`       | 01          |
| `Two`       | 02          |
| `Three`     | 03          |
| `Four`      | 04          |
| `Five`      | 05          |
| `Six`       | 06          |
| `Seven`     | 07          |
| `Eight`     | 08          |
| `Nine`      | 09          |
| `Ten`       | 10          |
| `Eleven`    | 11          |
| `Fourteen`  | 14          |
| `Fifteen`   | 15          |
| `Seventeen` | 17          |
| `Eighteen`  | 18          |
| `Nineteen`  | 19          |
| `Twenty`    | 20          |