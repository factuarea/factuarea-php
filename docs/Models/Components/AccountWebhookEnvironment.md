# AccountWebhookEnvironment

Environment of the endpoint: always the one of the credential that created it, and it never changes. A `live` credential never sees a `test` endpoint and the other way round. A `test` endpoint never reaches the network: its deliveries are recorded with their envelope and stay `pending`.


## Values

| Name   | Value  |
| ------ | ------ |
| `Live` | live   |
| `Test` | test   |