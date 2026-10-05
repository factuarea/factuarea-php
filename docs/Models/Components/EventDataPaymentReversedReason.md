# EventDataPaymentReversedReason

Reason the payment was reverted, from the closed catalog. `issued_in_error` is set only by annulling an invoice issued by mistake with `revert_collections` (`POST /v1/invoices/{invoice}/annul`); it cannot be requested for a single payment.


## Values

| Name                | Value               |
| ------------------- | ------------------- |
| `DirectDebitReturn` | direct_debit_return |
| `CardDispute`       | card_dispute        |
| `MisappliedPayment` | misapplied_payment  |
| `BouncedEffect`     | bounced_effect      |
| `RecordingError`    | recording_error     |
| `IssuedInError`     | issued_in_error     |