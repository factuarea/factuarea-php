# PublicApiV1AutomationsRunsListStatus

Status of the run. `dead_lettered` and `failed` are the ones worth replaying; `blocked` means a guardrail stopped it before executing. Exact match on `status`.


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Pending`      | pending        |
| `Running`      | running        |
| `Completed`    | completed      |
| `Failed`       | failed         |
| `DeadLettered` | dead_lettered  |
| `Blocked`      | blocked        |