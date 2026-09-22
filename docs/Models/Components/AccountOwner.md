# AccountOwner

The ownership of an account after a transfer. It carries NO `id` and that is not an omission: its identity is `account_id`, because the subject of the resource is the ownership of the account and not an entity of its own.


## Fields

| Field                                                                          | Type                                                                           | Required                                                                       | Description                                                                    |
| ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ------------------------------------------------------------------------------ |
| `accountId`                                                                    | *string*                                                                       | :heavy_check_mark:                                                             | Opaque identifier (UUID v7) of the account.                                    |
| `object`                                                                       | [Components\AccountOwnerObject](../../Models/Components/AccountOwnerObject.md) | :heavy_check_mark:                                                             | Always `account_owner`.                                                        |
| `ownerUserId`                                                                  | *string*                                                                       | :heavy_check_mark:                                                             | Opaque identifier (UUID v7) of the person who now owns the account.            |
| `previousOwnerUserId`                                                          | *string*                                                                       | :heavy_check_mark:                                                             | Opaque identifier (UUID v7) of the person who owned it before.                 |