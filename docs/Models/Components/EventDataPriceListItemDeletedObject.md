# EventDataPriceListItemDeletedObject

The price that stopped applying (deleted, withdrawn by a catalog deletion, or purged). It can no longer be read as an active price, so the event carries `{ id, object, price_list_id, deleted: true }`; a withdrawn price that is still readable arrives as the full price entry with `status: retired` and `deleted: true`.


## Supported Types

### `Components\PriceListItem`

```php
/**
* @var \Factuarea\Sdk\Models\Components\PriceListItem
*/
Components\PriceListItem $value = /* values here */
```

### `Components\EventPriceListItemDeletedObject`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventPriceListItemDeletedObject
*/
Components\EventPriceListItemDeletedObject $value = /* values here */
```

