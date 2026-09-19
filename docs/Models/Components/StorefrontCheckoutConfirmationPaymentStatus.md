# StorefrontCheckoutConfirmationPaymentStatus

Status of the COLLECTION after the confirmation, out of the same five-value catalog the order publishes. `paid` is the ordinary outcome —money taken, invoice issued and payment recorded against it—; `verified` is money taken with the issuing REJECTED (an overlap with recurring invoicing, an incomplete buyer identity, an exhausted series), which is a legitimate outcome published as it is instead of inventing an intermediate state; `simulated` is a company in test mode, where the lane runs end to end and produces no effect. A guard that is not met publishes here whatever the order already was.


## Values

| Name         | Value        |
| ------------ | ------------ |
| `NotStarted` | not_started  |
| `LinkIssued` | link_issued  |
| `Verified`   | verified     |
| `Paid`       | paid         |
| `Simulated`  | simulated    |