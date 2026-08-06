# EventDataRecurringInvoiceDeletedObject

Snapshot of the resource at emission time. When the resource is still recoverable the full snapshot is emitted with an additional `deleted: true` key; otherwise it degrades to `{ id, deleted: true }`.


## Supported Types

### `Components\RecurringInvoice`

```php
/**
* @var \Factuarea\Sdk\Models\Components\RecurringInvoice
*/
Components\RecurringInvoice $value = /* values here */
```

### `Components\EventDeletedObject`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDeletedObject
*/
Components\EventDeletedObject $value = /* values here */
```

