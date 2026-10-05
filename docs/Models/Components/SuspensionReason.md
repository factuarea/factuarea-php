# SuspensionReason

Why the system suspended this `inactive` tax ID, or `null` when it is not suspended by the system (active, archived, or deactivated by the account itself). `payer_blocked`: the account paying for this managed account has its billing blocked; it resumes when the payer settles. `management_ended`: the management of this managed account ended before anyone took over the payment; it resumes when the holder of the account takes over the payment.


## Values

| Name              | Value             |
| ----------------- | ----------------- |
| `PayerBlocked`    | payer_blocked     |
| `ManagementEnded` | management_ended  |