# RejectReturnRequestRejectionReason

Why the return is turned down: `window_expired` (the period for returning it had passed), `exceeds_delivered` (it asks for more than was delivered) or `manual_decision` (somebody decided so). The first two are the two automatic reasons; only the third is a judgement call, and only the third admits a note.


## Values

| Name               | Value              |
| ------------------ | ------------------ |
| `WindowExpired`    | window_expired     |
| `ExceedsDelivered` | exceeds_delivered  |
| `ManualDecision`   | manual_decision    |