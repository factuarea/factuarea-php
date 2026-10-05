# EventDataTaskCommentDeletedObject

Snapshot of the resource at emission time. When the resource is still recoverable the full snapshot is emitted with an additional `deleted: true` key; otherwise it degrades to `{ id, deleted: true }`.


## Supported Types

### `Components\TaskComment`

```php
/**
* @var \Factuarea\Sdk\Models\Components\TaskComment
*/
Components\TaskComment $value = /* values here */
```

### `Components\EventDeletedObject`

```php
/**
* @var \Factuarea\Sdk\Models\Components\EventDeletedObject
*/
Components\EventDeletedObject $value = /* values here */
```

