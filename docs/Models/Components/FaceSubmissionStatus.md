# FaceSubmissionStatus

FACe processing status. `prepared` and `transmission_uncertain` precede the FACe acknowledgement; afterwards `submitted`, `registered_rcf`, `accounted`, `paid`, `rejected`, `cancellation_requested`, `cancelled` or `error`. Kept up to date by the system polling — there is no refresh endpoint in v1.


## Values

| Name                    | Value                   |
| ----------------------- | ----------------------- |
| `Prepared`              | prepared                |
| `TransmissionUncertain` | transmission_uncertain  |
| `Submitted`             | submitted               |
| `RegisteredRcf`         | registered_rcf          |
| `Accounted`             | accounted               |
| `Paid`                  | paid                    |
| `Rejected`              | rejected                |
| `CancellationRequested` | cancellation_requested  |
| `Cancelled`             | cancelled               |
| `Error`                 | error                   |