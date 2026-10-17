# ManagedAccountImportRowCustomersCode

Reason of `customers_status: blocked`: the row itself was not applied (`nif_not_applied`), your account does not `operate` the managed account, the issuer is not an owner or admin of your own tax ID, your own tax ID is outside the tax ID scope of the key and was not registered by this import (`nif_not_in_key_scope`), the key lacks `contacts:write`, or the contact importer failed.


## Values

| Name                    | Value                   |
| ----------------------- | ----------------------- |
| `NifNotApplied`         | nif_not_applied         |
| `OperateAccessRequired` | operate_access_required |
| `AccountAdminRequired`  | account_admin_required  |
| `NifNotInKeyScope`      | nif_not_in_key_scope    |
| `InsufficientScope`     | insufficient_scope      |
| `CustomersImportFailed` | customers_import_failed |