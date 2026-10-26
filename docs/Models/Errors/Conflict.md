# Conflict

The request conflicts with data that already exists or with a request running at the same time. `invoice_administrative_version_conflict`: the administrative routing of the invoice changed since the `expected_version` you sent. `details.current_version` is the current one and `details.recovery` is `reload_or_keep_local`: read the administrative revisions again and repeat the request. Nothing was changed.


## Supported Types

### `Errors\PublicApiV1InvoicesAdministrativeRevisionsStoreResponseBodyException`

```php
/**
* @var \Factuarea\Sdk\Models\Errors\PublicApiV1InvoicesAdministrativeRevisionsStoreResponseBodyException
*/
Errors\PublicApiV1InvoicesAdministrativeRevisionsStoreResponseBodyException $value = /* values here */
```

### `Errors\Error`

```php
/**
* @var \Factuarea\Sdk\Models\Errors\Error
*/
Errors\Error $value = /* values here */
```

