# WebhookEventPayload

The event envelope delivered to a webhook endpoint, discriminated by `type`. Each variant carries the typed `data` payload for its event type.


## Supported Types

### `Components\WebhookEventPayloadInvoiceCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceCreated
*/
Components\WebhookEventPayloadInvoiceCreated $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceUpdated
*/
Components\WebhookEventPayloadInvoiceUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceSent
*/
Components\WebhookEventPayloadInvoiceSent $value = /* values here */
```

### `Components\WebhookEventPayloadInvoicePaid`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoicePaid
*/
Components\WebhookEventPayloadInvoicePaid $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceCancelled
*/
Components\WebhookEventPayloadInvoiceCancelled $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceAnnulled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceAnnulled
*/
Components\WebhookEventPayloadInvoiceAnnulled $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceOverdue`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceOverdue
*/
Components\WebhookEventPayloadInvoiceOverdue $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceDeleted
*/
Components\WebhookEventPayloadInvoiceDeleted $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceNumberAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceNumberAssigned
*/
Components\WebhookEventPayloadInvoiceNumberAssigned $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceRectified`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceRectified
*/
Components\WebhookEventPayloadInvoiceRectified $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceEmailSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceEmailSent
*/
Components\WebhookEventPayloadInvoiceEmailSent $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceEmailFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceEmailFailed
*/
Components\WebhookEventPayloadInvoiceEmailFailed $value = /* values here */
```

### `Components\WebhookEventPayloadInvoicePaymentReminderSent`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoicePaymentReminderSent
*/
Components\WebhookEventPayloadInvoicePaymentReminderSent $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceSimplifiedCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceSimplifiedCreated
*/
Components\WebhookEventPayloadInvoiceSimplifiedCreated $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceSimplifiedSubstituted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceSimplifiedSubstituted
*/
Components\WebhookEventPayloadInvoiceSimplifiedSubstituted $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceSubstitutedByComplete`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceSubstitutedByComplete
*/
Components\WebhookEventPayloadInvoiceSubstitutedByComplete $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceVerifactuSubmitted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceVerifactuSubmitted
*/
Components\WebhookEventPayloadInvoiceVerifactuSubmitted $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceVerifactuFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceVerifactuFailed
*/
Components\WebhookEventPayloadInvoiceVerifactuFailed $value = /* values here */
```

### `Components\WebhookEventPayloadInvoiceMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadInvoiceMetadataChanged
*/
Components\WebhookEventPayloadInvoiceMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteCreated
*/
Components\WebhookEventPayloadQuoteCreated $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteUpdated
*/
Components\WebhookEventPayloadQuoteUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteDeleted
*/
Components\WebhookEventPayloadQuoteDeleted $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteApproved`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteApproved
*/
Components\WebhookEventPayloadQuoteApproved $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteRejected`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteRejected
*/
Components\WebhookEventPayloadQuoteRejected $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteConverted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteConverted
*/
Components\WebhookEventPayloadQuoteConverted $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteExpired`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteExpired
*/
Components\WebhookEventPayloadQuoteExpired $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteMarkedAsPending`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteMarkedAsPending
*/
Components\WebhookEventPayloadQuoteMarkedAsPending $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteCancelled
*/
Components\WebhookEventPayloadQuoteCancelled $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteNumberAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteNumberAssigned
*/
Components\WebhookEventPayloadQuoteNumberAssigned $value = /* values here */
```

### `Components\WebhookEventPayloadQuoteMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadQuoteMetadataChanged
*/
Components\WebhookEventPayloadQuoteMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadProformaCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaCreated
*/
Components\WebhookEventPayloadProformaCreated $value = /* values here */
```

### `Components\WebhookEventPayloadProformaUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaUpdated
*/
Components\WebhookEventPayloadProformaUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadProformaDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaDeleted
*/
Components\WebhookEventPayloadProformaDeleted $value = /* values here */
```

### `Components\WebhookEventPayloadProformaAccepted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaAccepted
*/
Components\WebhookEventPayloadProformaAccepted $value = /* values here */
```

### `Components\WebhookEventPayloadProformaRejected`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaRejected
*/
Components\WebhookEventPayloadProformaRejected $value = /* values here */
```

### `Components\WebhookEventPayloadProformaCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaCancelled
*/
Components\WebhookEventPayloadProformaCancelled $value = /* values here */
```

### `Components\WebhookEventPayloadProformaExpired`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaExpired
*/
Components\WebhookEventPayloadProformaExpired $value = /* values here */
```

### `Components\WebhookEventPayloadProformaConvertedToInvoice`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaConvertedToInvoice
*/
Components\WebhookEventPayloadProformaConvertedToInvoice $value = /* values here */
```

### `Components\WebhookEventPayloadProformaNumberAssigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaNumberAssigned
*/
Components\WebhookEventPayloadProformaNumberAssigned $value = /* values here */
```

### `Components\WebhookEventPayloadProformaMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProformaMetadataChanged
*/
Components\WebhookEventPayloadProformaMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadDeliveryNoteCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadDeliveryNoteCreated
*/
Components\WebhookEventPayloadDeliveryNoteCreated $value = /* values here */
```

### `Components\WebhookEventPayloadDeliveryNoteUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadDeliveryNoteUpdated
*/
Components\WebhookEventPayloadDeliveryNoteUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadDeliveryNoteStatusChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadDeliveryNoteStatusChanged
*/
Components\WebhookEventPayloadDeliveryNoteStatusChanged $value = /* values here */
```

### `Components\WebhookEventPayloadDeliveryNoteSigned`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadDeliveryNoteSigned
*/
Components\WebhookEventPayloadDeliveryNoteSigned $value = /* values here */
```

### `Components\WebhookEventPayloadDeliveryNoteConverted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadDeliveryNoteConverted
*/
Components\WebhookEventPayloadDeliveryNoteConverted $value = /* values here */
```

### `Components\WebhookEventPayloadPurchaseInvoiceCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadPurchaseInvoiceCreated
*/
Components\WebhookEventPayloadPurchaseInvoiceCreated $value = /* values here */
```

### `Components\WebhookEventPayloadPurchaseInvoiceUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadPurchaseInvoiceUpdated
*/
Components\WebhookEventPayloadPurchaseInvoiceUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadPurchaseInvoicePaid`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadPurchaseInvoicePaid
*/
Components\WebhookEventPayloadPurchaseInvoicePaid $value = /* values here */
```

### `Components\WebhookEventPayloadPurchaseInvoiceCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadPurchaseInvoiceCancelled
*/
Components\WebhookEventPayloadPurchaseInvoiceCancelled $value = /* values here */
```

### `Components\WebhookEventPayloadPurchaseInvoiceMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadPurchaseInvoiceMetadataChanged
*/
Components\WebhookEventPayloadPurchaseInvoiceMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceCreated
*/
Components\WebhookEventPayloadRecurringInvoiceCreated $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceActivated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceActivated
*/
Components\WebhookEventPayloadRecurringInvoiceActivated $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoicePaused`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoicePaused
*/
Components\WebhookEventPayloadRecurringInvoicePaused $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceUpdated
*/
Components\WebhookEventPayloadRecurringInvoiceUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceDeleted
*/
Components\WebhookEventPayloadRecurringInvoiceDeleted $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceCompleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceCompleted
*/
Components\WebhookEventPayloadRecurringInvoiceCompleted $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceExecuted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceExecuted
*/
Components\WebhookEventPayloadRecurringInvoiceExecuted $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceFailed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceFailed
*/
Components\WebhookEventPayloadRecurringInvoiceFailed $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceMetadataChanged
*/
Components\WebhookEventPayloadRecurringInvoiceMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadRecurringInvoiceCancelled`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadRecurringInvoiceCancelled
*/
Components\WebhookEventPayloadRecurringInvoiceCancelled $value = /* values here */
```

### `Components\WebhookEventPayloadClientCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadClientCreated
*/
Components\WebhookEventPayloadClientCreated $value = /* values here */
```

### `Components\WebhookEventPayloadClientUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadClientUpdated
*/
Components\WebhookEventPayloadClientUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadClientDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadClientDeleted
*/
Components\WebhookEventPayloadClientDeleted $value = /* values here */
```

### `Components\WebhookEventPayloadClientMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadClientMetadataChanged
*/
Components\WebhookEventPayloadClientMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadProductCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProductCreated
*/
Components\WebhookEventPayloadProductCreated $value = /* values here */
```

### `Components\WebhookEventPayloadProductUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadProductUpdated
*/
Components\WebhookEventPayloadProductUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadPaymentReceived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadPaymentReceived
*/
Components\WebhookEventPayloadPaymentReceived $value = /* values here */
```

### `Components\WebhookEventPayloadTaxMetadataChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadTaxMetadataChanged
*/
Components\WebhookEventPayloadTaxMetadataChanged $value = /* values here */
```

### `Components\WebhookEventPayloadTaxValidityChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadTaxValidityChanged
*/
Components\WebhookEventPayloadTaxValidityChanged $value = /* values here */
```

### `Components\WebhookEventPayloadTaxExternalReferenceChanged`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadTaxExternalReferenceChanged
*/
Components\WebhookEventPayloadTaxExternalReferenceChanged $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesCreated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesCreated
*/
Components\WebhookEventPayloadSeriesCreated $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesUpdated`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesUpdated
*/
Components\WebhookEventPayloadSeriesUpdated $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesDeleted`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesDeleted
*/
Components\WebhookEventPayloadSeriesDeleted $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesArchived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesArchived
*/
Components\WebhookEventPayloadSeriesArchived $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesUnarchived`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesUnarchived
*/
Components\WebhookEventPayloadSeriesUnarchived $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesMarkedAsDefault`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesMarkedAsDefault
*/
Components\WebhookEventPayloadSeriesMarkedAsDefault $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesDemotedFromDefault`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesDemotedFromDefault
*/
Components\WebhookEventPayloadSeriesDemotedFromDefault $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesYearReset`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesYearReset
*/
Components\WebhookEventPayloadSeriesYearReset $value = /* values here */
```

### `Components\WebhookEventPayloadSeriesNumberConsumed`

```php
/**
* @var \Factuarea\Sdk\Models\Components\WebhookEventPayloadSeriesNumberConsumed
*/
Components\WebhookEventPayloadSeriesNumberConsumed $value = /* values here */
```

