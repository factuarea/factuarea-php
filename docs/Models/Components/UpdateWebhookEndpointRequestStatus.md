# UpdateWebhookEndpointRequestStatus

Optional endpoint status: `active` re-enables the endpoint and `disabled` pauses it; omit it to leave the status unchanged. `degraded` cannot be set by hand. Re-enabling an endpoint that was paused because its company tax ID was transferred to another account makes the caller its new owner, so the degraded-endpoint email reaches that person from then on.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Active`   | active     |
| `Disabled` | disabled   |