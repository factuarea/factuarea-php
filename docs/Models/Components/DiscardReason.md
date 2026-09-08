# DiscardReason

Typed reason why the event was discarded, from a CLOSED catalogue, or `null` when it was not discarded. This is the axis to filter and group by. A value retired from the catalogue in a later version is still returned verbatim here, but its `discard_reason_label`, `is_actionable` and `is_replayable` degrade to neutral rather than breaking the page.


## Values

| Name                                | Value                               |
| ----------------------------------- | ----------------------------------- |
| `EventNotNormalizable`              | event_not_normalizable              |
| `DuplicateRedelivery`               | duplicate_redelivery                |
| `ConnectedAccountMissing`           | connected_account_missing           |
| `ConnectedAccountUnknown`           | connected_account_unknown           |
| `SpontaneousPaymentMissingId`       | spontaneous_payment_missing_id      |
| `AutoinvoicingDisabled`             | autoinvoicing_disabled              |
| `UnsupportedCurrency`               | unsupported_currency                |
| `RefundWithoutItems`                | refund_without_items                |
| `RefundAutoinvoicingDisabled`       | refund_autoinvoicing_disabled       |
| `SubscriptionMissingInvoiceId`      | subscription_missing_invoice_id     |
| `SubscriptionProrationReview`       | subscription_proration_review       |
| `SubscriptionNotACycle`             | subscription_not_a_cycle            |
| `SubscriptionTrialSkipped`          | subscription_trial_skipped          |
| `SubscriptionAutoinvoicingDisabled` | subscription_autoinvoicing_disabled |
| `SubscriptionAlreadyInvoiced`       | subscription_already_invoiced       |
| `PayoutMissingId`                   | payout_missing_id                   |
| `PayoutConnectedAccountMissing`     | payout_connected_account_missing    |
| `PaymentFailed`                     | payment_failed                      |
| `EventTypeNotCovered`               | event_type_not_covered              |
| `CheckoutLinesRetrieveFailed`       | checkout_lines_retrieve_failed      |
| `ReversalPaymentNotFound`           | reversal_payment_not_found          |
| `ReversalAlreadyApplied`            | reversal_already_applied            |
| `DisputeInProgress`                 | dispute_in_progress                 |
| `DisputeResolved`                   | dispute_resolved                    |