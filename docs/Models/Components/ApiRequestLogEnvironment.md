# ApiRequestLogEnvironment

Environment of the key that issued the request: `live` (`fact_live_`) or `test` (`fact_test_`). Test keys operate on the sandbox company, so their logs belong to a different tenant and a live key never sees them; the filter is what lets a test key inspect its own traffic.


## Values

| Name   | Value  |
| ------ | ------ |
| `Live` | live   |
| `Test` | test   |