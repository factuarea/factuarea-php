# AutomationRunStatus

State of the run: `pending` (queued), `running`, `completed`, `failed` (finished without producing its effect and not reprocessable), `dead_lettered` (parked awaiting a manual replay) or `blocked` (stopped by a guardrail such as the chain-depth cap, the rate limit or the monthly budget).


## Values

| Name           | Value          |
| -------------- | -------------- |
| `Pending`      | pending        |
| `Running`      | running        |
| `Completed`    | completed      |
| `Failed`       | failed         |
| `DeadLettered` | dead_lettered  |
| `Blocked`      | blocked        |