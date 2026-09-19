# IntegrationEventDiscardReason

Typed reason why the event was discarded, from a CLOSED catalogue, or `null` when it was not discarded. This is the axis to filter and group by. A value retired from the catalogue in a later version is still returned verbatim here, but its `discard_reason_label`, `is_actionable` and `is_replayable` degrade to neutral rather than breaking the page.


## Values

| Name                                            | Value                                           |
| ----------------------------------------------- | ----------------------------------------------- |
| `EventNotNormalizable`                          | event_not_normalizable                          |
| `DuplicateRedelivery`                           | duplicate_redelivery                            |
| `ConnectedAccountMissing`                       | connected_account_missing                       |
| `ConnectedAccountUnknown`                       | connected_account_unknown                       |
| `SpontaneousPaymentMissingId`                   | spontaneous_payment_missing_id                  |
| `AutoinvoicingDisabled`                         | autoinvoicing_disabled                          |
| `UnsupportedCurrency`                           | unsupported_currency                            |
| `RefundWithoutItems`                            | refund_without_items                            |
| `RefundAutoinvoicingDisabled`                   | refund_autoinvoicing_disabled                   |
| `SubscriptionMissingInvoiceId`                  | subscription_missing_invoice_id                 |
| `SubscriptionProrationReview`                   | subscription_proration_review                   |
| `SubscriptionNotACycle`                         | subscription_not_a_cycle                        |
| `SubscriptionTrialSkipped`                      | subscription_trial_skipped                      |
| `SubscriptionAutoinvoicingDisabled`             | subscription_autoinvoicing_disabled             |
| `SubscriptionAlreadyInvoiced`                   | subscription_already_invoiced                   |
| `PayoutMissingId`                               | payout_missing_id                               |
| `PayoutConnectedAccountMissing`                 | payout_connected_account_missing                |
| `PaymentFailed`                                 | payment_failed                                  |
| `EventTypeNotCovered`                           | event_type_not_covered                          |
| `CheckoutLinesRetrieveFailed`                   | checkout_lines_retrieve_failed                  |
| `ReversalPaymentNotFound`                       | reversal_payment_not_found                      |
| `ReversalAlreadyApplied`                        | reversal_already_applied                        |
| `DisputeInProgress`                             | dispute_in_progress                             |
| `DisputeResolved`                               | dispute_resolved                                |
| `TestModeEvent`                                 | test_mode_event                                 |
| `OrderEventNotCovered`                          | order_event_not_covered                         |
| `StoreNotFound`                                 | store_not_found                                 |
| `StoreEnvironmentTest`                          | store_environment_test                          |
| `RefundBeforeOrder`                             | refund_before_order                             |
| `RefundReasonUnmapped`                          | refund_reason_unmapped                          |
| `RecurringInvoiceOverlap`                       | recurring_invoice_overlap                       |
| `SeriesDateClamped`                             | series_date_clamped                             |
| `StoreAutoinvoicingDisabled`                    | store_autoinvoicing_disabled                    |
| `VatResidualOutOfTolerance`                     | vat_residual_out_of_tolerance                   |
| `SimplifiedAbsoluteLimitExceeded`               | simplified_absolute_limit_exceeded              |
| `SimplifiedThresholdExceededWithoutRecipient`   | simplified_threshold_exceeded_without_recipient |
| `StoreRequiresTaxId`                            | store_requires_tax_id                           |
| `OrderLineAmountExceedsColumn`                  | order_line_amount_exceeds_column                |
| `OrderStatusUnknown`                            | order_status_unknown                            |
| `RefundNotSettled`                              | refund_not_settled                              |
| `ProtectedCustomerDataUnavailable`              | protected_customer_data_unavailable             |
| `StorefrontOrderPayment`                        | storefront_order_payment                        |
| `InterposedOrderNotCreatable`                   | interposed_order_not_creatable                  |