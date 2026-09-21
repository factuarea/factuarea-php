# EventData

The payload embedded when the event was emitted, discriminated by `type`. Carries the full snapshot of the affected resource under `object` — identical to `GET /v1/<resource>/{id}` at emission time — plus event-specific keys, e.g. `{ "type": "invoice.paid", "object": { ...invoice... }, "amount": 1210.0 }`. The snapshot is frozen: later changes to the resource do not rewrite past events.


## Supported Types

### `Components\EventDataInvoiceCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceCreated
*/
Components\EventDataInvoiceCreated $value = /* values here */
```

### `Components\EventDataInvoiceAutoCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceAutoCreated
*/
Components\EventDataInvoiceAutoCreated $value = /* values here */
```

### `Components\EventDataInvoiceCorrectiveAutoCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceCorrectiveAutoCreated
*/
Components\EventDataInvoiceCorrectiveAutoCreated $value = /* values here */
```

### `Components\EventDataInvoiceSubscriptionAutoCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceSubscriptionAutoCreated
*/
Components\EventDataInvoiceSubscriptionAutoCreated $value = /* values here */
```

### `Components\EventDataInvoiceUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceUpdated
*/
Components\EventDataInvoiceUpdated $value = /* values here */
```

### `Components\EventDataInvoiceSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceSent
*/
Components\EventDataInvoiceSent $value = /* values here */
```

### `Components\EventDataInvoicePaid`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoicePaid
*/
Components\EventDataInvoicePaid $value = /* values here */
```

### `Components\EventDataInvoiceCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceCancelled
*/
Components\EventDataInvoiceCancelled $value = /* values here */
```

### `Components\EventDataInvoiceAnnulled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceAnnulled
*/
Components\EventDataInvoiceAnnulled $value = /* values here */
```

### `Components\EventDataInvoiceOverdue`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceOverdue
*/
Components\EventDataInvoiceOverdue $value = /* values here */
```

### `Components\EventDataInvoiceDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceDeleted
*/
Components\EventDataInvoiceDeleted $value = /* values here */
```

### `Components\EventDataInvoiceNumberAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceNumberAssigned
*/
Components\EventDataInvoiceNumberAssigned $value = /* values here */
```

### `Components\EventDataInvoiceRectified`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceRectified
*/
Components\EventDataInvoiceRectified $value = /* values here */
```

### `Components\EventDataInvoiceEmailSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceEmailSent
*/
Components\EventDataInvoiceEmailSent $value = /* values here */
```

### `Components\EventDataInvoiceEmailFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceEmailFailed
*/
Components\EventDataInvoiceEmailFailed $value = /* values here */
```

### `Components\EventDataInvoicePaymentReminderSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoicePaymentReminderSent
*/
Components\EventDataInvoicePaymentReminderSent $value = /* values here */
```

### `Components\EventDataInvoiceSimplifiedCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceSimplifiedCreated
*/
Components\EventDataInvoiceSimplifiedCreated $value = /* values here */
```

### `Components\EventDataInvoiceSimplifiedSubstituted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceSimplifiedSubstituted
*/
Components\EventDataInvoiceSimplifiedSubstituted $value = /* values here */
```

### `Components\EventDataInvoiceSubstitutedByComplete`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceSubstitutedByComplete
*/
Components\EventDataInvoiceSubstitutedByComplete $value = /* values here */
```

### `Components\EventDataInvoiceVerifactuSubmitted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceVerifactuSubmitted
*/
Components\EventDataInvoiceVerifactuSubmitted $value = /* values here */
```

### `Components\EventDataInvoiceVerifactuFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceVerifactuFailed
*/
Components\EventDataInvoiceVerifactuFailed $value = /* values here */
```

### `Components\EventDataInvoiceMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataInvoiceMetadataChanged
*/
Components\EventDataInvoiceMetadataChanged $value = /* values here */
```

### `Components\EventDataQuoteCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteCreated
*/
Components\EventDataQuoteCreated $value = /* values here */
```

### `Components\EventDataQuoteUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteUpdated
*/
Components\EventDataQuoteUpdated $value = /* values here */
```

### `Components\EventDataQuoteDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteDeleted
*/
Components\EventDataQuoteDeleted $value = /* values here */
```

### `Components\EventDataQuoteApproved`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteApproved
*/
Components\EventDataQuoteApproved $value = /* values here */
```

### `Components\EventDataQuoteRejected`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteRejected
*/
Components\EventDataQuoteRejected $value = /* values here */
```

### `Components\EventDataQuoteConverted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteConverted
*/
Components\EventDataQuoteConverted $value = /* values here */
```

### `Components\EventDataQuoteExpired`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteExpired
*/
Components\EventDataQuoteExpired $value = /* values here */
```

### `Components\EventDataQuoteMarkedAsPending`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteMarkedAsPending
*/
Components\EventDataQuoteMarkedAsPending $value = /* values here */
```

### `Components\EventDataQuoteCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteCancelled
*/
Components\EventDataQuoteCancelled $value = /* values here */
```

### `Components\EventDataQuoteNumberAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteNumberAssigned
*/
Components\EventDataQuoteNumberAssigned $value = /* values here */
```

### `Components\EventDataQuoteMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteMetadataChanged
*/
Components\EventDataQuoteMetadataChanged $value = /* values here */
```

### `Components\EventDataQuoteEmailSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteEmailSent
*/
Components\EventDataQuoteEmailSent $value = /* values here */
```

### `Components\EventDataQuoteEmailFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataQuoteEmailFailed
*/
Components\EventDataQuoteEmailFailed $value = /* values here */
```

### `Components\EventDataProformaCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaCreated
*/
Components\EventDataProformaCreated $value = /* values here */
```

### `Components\EventDataProformaUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaUpdated
*/
Components\EventDataProformaUpdated $value = /* values here */
```

### `Components\EventDataProformaDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaDeleted
*/
Components\EventDataProformaDeleted $value = /* values here */
```

### `Components\EventDataProformaAccepted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaAccepted
*/
Components\EventDataProformaAccepted $value = /* values here */
```

### `Components\EventDataProformaRejected`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaRejected
*/
Components\EventDataProformaRejected $value = /* values here */
```

### `Components\EventDataProformaCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaCancelled
*/
Components\EventDataProformaCancelled $value = /* values here */
```

### `Components\EventDataProformaExpired`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaExpired
*/
Components\EventDataProformaExpired $value = /* values here */
```

### `Components\EventDataProformaConvertedToInvoice`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaConvertedToInvoice
*/
Components\EventDataProformaConvertedToInvoice $value = /* values here */
```

### `Components\EventDataProformaNumberAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaNumberAssigned
*/
Components\EventDataProformaNumberAssigned $value = /* values here */
```

### `Components\EventDataProformaMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaMetadataChanged
*/
Components\EventDataProformaMetadataChanged $value = /* values here */
```

### `Components\EventDataProformaEmailSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaEmailSent
*/
Components\EventDataProformaEmailSent $value = /* values here */
```

### `Components\EventDataProformaEmailFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProformaEmailFailed
*/
Components\EventDataProformaEmailFailed $value = /* values here */
```

### `Components\EventDataDeliveryNoteCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteCreated
*/
Components\EventDataDeliveryNoteCreated $value = /* values here */
```

### `Components\EventDataDeliveryNoteUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteUpdated
*/
Components\EventDataDeliveryNoteUpdated $value = /* values here */
```

### `Components\EventDataDeliveryNoteStatusChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteStatusChanged
*/
Components\EventDataDeliveryNoteStatusChanged $value = /* values here */
```

### `Components\EventDataDeliveryNoteSigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteSigned
*/
Components\EventDataDeliveryNoteSigned $value = /* values here */
```

### `Components\EventDataDeliveryNoteConverted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteConverted
*/
Components\EventDataDeliveryNoteConverted $value = /* values here */
```

### `Components\EventDataDeliveryNoteEmailSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteEmailSent
*/
Components\EventDataDeliveryNoteEmailSent $value = /* values here */
```

### `Components\EventDataDeliveryNoteEmailFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataDeliveryNoteEmailFailed
*/
Components\EventDataDeliveryNoteEmailFailed $value = /* values here */
```

### `Components\EventDataPurchaseInvoiceCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPurchaseInvoiceCreated
*/
Components\EventDataPurchaseInvoiceCreated $value = /* values here */
```

### `Components\EventDataPurchaseInvoiceUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPurchaseInvoiceUpdated
*/
Components\EventDataPurchaseInvoiceUpdated $value = /* values here */
```

### `Components\EventDataPurchaseInvoicePaid`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPurchaseInvoicePaid
*/
Components\EventDataPurchaseInvoicePaid $value = /* values here */
```

### `Components\EventDataPurchaseInvoiceCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPurchaseInvoiceCancelled
*/
Components\EventDataPurchaseInvoiceCancelled $value = /* values here */
```

### `Components\EventDataPurchaseInvoiceMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPurchaseInvoiceMetadataChanged
*/
Components\EventDataPurchaseInvoiceMetadataChanged $value = /* values here */
```

### `Components\EventDataPurchaseInvoicePaymentRegistered`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPurchaseInvoicePaymentRegistered
*/
Components\EventDataPurchaseInvoicePaymentRegistered $value = /* values here */
```

### `Components\EventDataRecurringInvoiceCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceCreated
*/
Components\EventDataRecurringInvoiceCreated $value = /* values here */
```

### `Components\EventDataRecurringInvoiceActivated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceActivated
*/
Components\EventDataRecurringInvoiceActivated $value = /* values here */
```

### `Components\EventDataRecurringInvoicePaused`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoicePaused
*/
Components\EventDataRecurringInvoicePaused $value = /* values here */
```

### `Components\EventDataRecurringInvoiceUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceUpdated
*/
Components\EventDataRecurringInvoiceUpdated $value = /* values here */
```

### `Components\EventDataRecurringInvoiceDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceDeleted
*/
Components\EventDataRecurringInvoiceDeleted $value = /* values here */
```

### `Components\EventDataRecurringInvoiceCompleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceCompleted
*/
Components\EventDataRecurringInvoiceCompleted $value = /* values here */
```

### `Components\EventDataRecurringInvoiceExecuted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceExecuted
*/
Components\EventDataRecurringInvoiceExecuted $value = /* values here */
```

### `Components\EventDataRecurringInvoiceFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceFailed
*/
Components\EventDataRecurringInvoiceFailed $value = /* values here */
```

### `Components\EventDataRecurringInvoiceMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceMetadataChanged
*/
Components\EventDataRecurringInvoiceMetadataChanged $value = /* values here */
```

### `Components\EventDataRecurringInvoiceCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataRecurringInvoiceCancelled
*/
Components\EventDataRecurringInvoiceCancelled $value = /* values here */
```

### `Components\EventDataClientCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataClientCreated
*/
Components\EventDataClientCreated $value = /* values here */
```

### `Components\EventDataClientUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataClientUpdated
*/
Components\EventDataClientUpdated $value = /* values here */
```

### `Components\EventDataClientDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataClientDeleted
*/
Components\EventDataClientDeleted $value = /* values here */
```

### `Components\EventDataClientMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataClientMetadataChanged
*/
Components\EventDataClientMetadataChanged $value = /* values here */
```

### `Components\EventDataContactCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactCreated
*/
Components\EventDataContactCreated $value = /* values here */
```

### `Components\EventDataContactUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactUpdated
*/
Components\EventDataContactUpdated $value = /* values here */
```

### `Components\EventDataContactArchived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactArchived
*/
Components\EventDataContactArchived $value = /* values here */
```

### `Components\EventDataContactRestored`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactRestored
*/
Components\EventDataContactRestored $value = /* values here */
```

### `Components\EventDataContactDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactDeleted
*/
Components\EventDataContactDeleted $value = /* values here */
```

### `Components\EventDataContactRoleAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactRoleAssigned
*/
Components\EventDataContactRoleAssigned $value = /* values here */
```

### `Components\EventDataContactRoleActivated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactRoleActivated
*/
Components\EventDataContactRoleActivated $value = /* values here */
```

### `Components\EventDataContactRoleDeactivated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactRoleDeactivated
*/
Components\EventDataContactRoleDeactivated $value = /* values here */
```

### `Components\EventDataContactRoleRemoved`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactRoleRemoved
*/
Components\EventDataContactRoleRemoved $value = /* values here */
```

### `Components\EventDataContactCustomerProfileUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactCustomerProfileUpdated
*/
Components\EventDataContactCustomerProfileUpdated $value = /* values here */
```

### `Components\EventDataContactSupplierProfileUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataContactSupplierProfileUpdated
*/
Components\EventDataContactSupplierProfileUpdated $value = /* values here */
```

### `Components\EventDataProductCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProductCreated
*/
Components\EventDataProductCreated $value = /* values here */
```

### `Components\EventDataProductUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataProductUpdated
*/
Components\EventDataProductUpdated $value = /* values here */
```

### `Components\EventDataPaymentReceived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPaymentReceived
*/
Components\EventDataPaymentReceived $value = /* values here */
```

### `Components\EventDataPaymentReversed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPaymentReversed
*/
Components\EventDataPaymentReversed $value = /* values here */
```

### `Components\EventDataTaxMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataTaxMetadataChanged
*/
Components\EventDataTaxMetadataChanged $value = /* values here */
```

### `Components\EventDataTaxValidityChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataTaxValidityChanged
*/
Components\EventDataTaxValidityChanged $value = /* values here */
```

### `Components\EventDataTaxExternalReferenceChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataTaxExternalReferenceChanged
*/
Components\EventDataTaxExternalReferenceChanged $value = /* values here */
```

### `Components\EventDataSeriesCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesCreated
*/
Components\EventDataSeriesCreated $value = /* values here */
```

### `Components\EventDataSeriesUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesUpdated
*/
Components\EventDataSeriesUpdated $value = /* values here */
```

### `Components\EventDataSeriesDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesDeleted
*/
Components\EventDataSeriesDeleted $value = /* values here */
```

### `Components\EventDataSeriesArchived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesArchived
*/
Components\EventDataSeriesArchived $value = /* values here */
```

### `Components\EventDataSeriesUnarchived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesUnarchived
*/
Components\EventDataSeriesUnarchived $value = /* values here */
```

### `Components\EventDataSeriesMarkedAsDefault`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesMarkedAsDefault
*/
Components\EventDataSeriesMarkedAsDefault $value = /* values here */
```

### `Components\EventDataSeriesDemotedFromDefault`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesDemotedFromDefault
*/
Components\EventDataSeriesDemotedFromDefault $value = /* values here */
```

### `Components\EventDataSeriesYearReset`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesYearReset
*/
Components\EventDataSeriesYearReset $value = /* values here */
```

### `Components\EventDataSeriesMonthReset`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesMonthReset
*/
Components\EventDataSeriesMonthReset $value = /* values here */
```

### `Components\EventDataSeriesNumberConsumed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataSeriesNumberConsumed
*/
Components\EventDataSeriesNumberConsumed $value = /* values here */
```

### `Components\EventDataFacturaeFaceSubmitted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataFacturaeFaceSubmitted
*/
Components\EventDataFacturaeFaceSubmitted $value = /* values here */
```

### `Components\EventDataFacturaeFaceStatusChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataFacturaeFaceStatusChanged
*/
Components\EventDataFacturaeFaceStatusChanged $value = /* values here */
```

### `Components\EventDataFacturaeFaceCancellationRequested`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataFacturaeFaceCancellationRequested
*/
Components\EventDataFacturaeFaceCancellationRequested $value = /* values here */
```

### `Components\EventDataPayoutReconciled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataPayoutReconciled
*/
Components\EventDataPayoutReconciled $value = /* values here */
```

### `Components\EventDataEmployeeCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataEmployeeCreated
*/
Components\EventDataEmployeeCreated $value = /* values here */
```

### `Components\EventDataEmployeeUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataEmployeeUpdated
*/
Components\EventDataEmployeeUpdated $value = /* values here */
```

### `Components\EventDataEmployeeDeactivated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataEmployeeDeactivated
*/
Components\EventDataEmployeeDeactivated $value = /* values here */
```

### `Components\EventDataEmployeeInvited`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataEmployeeInvited
*/
Components\EventDataEmployeeInvited $value = /* values here */
```

### `Components\EventDataTimeEntryRecorded`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataTimeEntryRecorded
*/
Components\EventDataTimeEntryRecorded $value = /* values here */
```

### `Components\EventDataTimeEntryCorrected`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataTimeEntryCorrected
*/
Components\EventDataTimeEntryCorrected $value = /* values here */
```

### `Components\EventDataAbsenceRequested`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAbsenceRequested
*/
Components\EventDataAbsenceRequested $value = /* values here */
```

### `Components\EventDataAbsenceApproved`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAbsenceApproved
*/
Components\EventDataAbsenceApproved $value = /* values here */
```

### `Components\EventDataAbsenceRejected`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAbsenceRejected
*/
Components\EventDataAbsenceRejected $value = /* values here */
```

### `Components\EventDataMonthlyRegisterClosed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataMonthlyRegisterClosed
*/
Components\EventDataMonthlyRegisterClosed $value = /* values here */
```

### `Components\EventDataAutomationRuleActivated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRuleActivated
*/
Components\EventDataAutomationRuleActivated $value = /* values here */
```

### `Components\EventDataAutomationRulePaused`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRulePaused
*/
Components\EventDataAutomationRulePaused $value = /* values here */
```

### `Components\EventDataAutomationRuleAutoPaused`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRuleAutoPaused
*/
Components\EventDataAutomationRuleAutoPaused $value = /* values here */
```

### `Components\EventDataAutomationRunStarted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRunStarted
*/
Components\EventDataAutomationRunStarted $value = /* values here */
```

### `Components\EventDataAutomationRunCompleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRunCompleted
*/
Components\EventDataAutomationRunCompleted $value = /* values here */
```

### `Components\EventDataAutomationRunFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRunFailed
*/
Components\EventDataAutomationRunFailed $value = /* values here */
```

### `Components\EventDataAutomationRunStepDeadLettered`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataAutomationRunStepDeadLettered
*/
Components\EventDataAutomationRunStepDeadLettered $value = /* values here */
```

### `Components\EventDataOrderInvoiced`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataOrderInvoiced
*/
Components\EventDataOrderInvoiced $value = /* values here */
```

### `Components\EventDataOrderRefunded`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDataOrderRefunded
*/
Components\EventDataOrderRefunded $value = /* values here */
```

