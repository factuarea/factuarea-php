# IntegrationEventStatus

Outcome of the event: `success` (it produced its effect), `skipped` (it was discarded on purpose — see `discard_reason`) or `failure` (it broke while being processed — see `error_message`).


## Values

| Name      | Value     |
| --------- | --------- |
| `Success` | success   |
| `Skipped` | skipped   |
| `Failure` | failure   |