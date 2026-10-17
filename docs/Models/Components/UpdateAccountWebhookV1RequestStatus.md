# UpdateAccountWebhookV1RequestStatus

Optional endpoint status: `active` re-enables the endpoint and `disabled` pauses it; omit it to leave the status unchanged. Cannot be `null`. Re-enabling an endpoint that the engine disabled because its creator lost the capability makes the caller its new creator.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Active`   | active     |
| `Disabled` | disabled   |