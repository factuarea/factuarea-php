# AddAccountMemberV1RequestGrant

Grant on one tax ID of your account: its identifier and its access level.


## Fields

| Field                                                                                                  | Type                                                                                                   | Required                                                                                               | Description                                                                                            |
| ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ |
| `companyId`                                                                                            | *string*                                                                                               | :heavy_check_mark:                                                                                     | Public identifier (UUID v7) of a tax ID of your account.                                               |
| `level`                                                                                                | [Components\AddAccountMemberV1RequestLevel](../../Models/Components/AddAccountMemberV1RequestLevel.md) | :heavy_check_mark:                                                                                     | Access level on that tax ID: `view` (read-only) or `operate`.                                          |