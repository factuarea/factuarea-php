# ManagedAccountImportRowStatus

`VALID` (it will be / was applied), `WARNING` (it will be / was applied with a remark in `code`), `ALREADY_EXISTS` (the account or the tax ID already exists: it is reconciled, never duplicated nor charged twice), `BLOCKED` (a business rule prevents applying it; the reason is in `code`) or `ERROR` (the row is wrong; the columns are in `errors`, or the reason in `code`). Only `VALID`, `WARNING` and `ALREADY_EXISTS` rows are applied.


## Values

| Name            | Value           |
| --------------- | --------------- |
| `Valid`         | VALID           |
| `Warning`       | WARNING         |
| `AlreadyExists` | ALREADY_EXISTS  |
| `Blocked`       | BLOCKED         |
| `Error`         | ERROR           |