# ReversalReason

Reason the payment was reverted, from the closed catalog. `issued_in_error` appears only on payments reverted by annulling an invoice issued by mistake with `revert_collections`; it cannot be requested for a single payment. `null` while the payment is in force.


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `DirectDebitReturn` | direct_debit_return |
| `CardDispute`       | card_dispute        |
| `MisappliedPayment` | misapplied_payment  |
| `BouncedEffect`     | bounced_effect      |
| `RecordingError`    | recording_error     |
| `IssuedInError`     | issued_in_error     |