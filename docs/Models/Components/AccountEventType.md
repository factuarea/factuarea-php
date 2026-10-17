# AccountEventType

Type of an account-level event (the `account.*` catalog). Only account webhook endpoints can subscribe to them: they are not part of the company event catalog (`GET /v1/event-catalog`). Members: `account.member_added`, `account.member_removed`, `account.member_role_changed`, `account.owner_transferred` and `account.member_grant_changed`. Relations between accounts: `account.access_granted`, `account.access_revoked` and `account.access_level_changed`. Managed accounts: `account.managed_account_provisioned`, `account.managed_account_claimed` and `account.management_ended`. Tax IDs of the account: `account.nif_added`, `account.nif_transferred` and `account.nif_archived`. Bulk imports: `account.import_completed`.


## Values

| Name                                | Value                               |
| ----------------------------------- | ----------------------------------- |
| `AccountMemberAdded`                | account.member_added                |
| `AccountMemberRemoved`              | account.member_removed              |
| `AccountMemberRoleChanged`          | account.member_role_changed         |
| `AccountOwnerTransferred`           | account.owner_transferred           |
| `AccountMemberGrantChanged`         | account.member_grant_changed        |
| `AccountAccessGranted`              | account.access_granted              |
| `AccountAccessRevoked`              | account.access_revoked              |
| `AccountAccessLevelChanged`         | account.access_level_changed        |
| `AccountManagedAccountProvisioned`  | account.managed_account_provisioned |
| `AccountManagedAccountClaimed`      | account.managed_account_claimed     |
| `AccountManagementEnded`            | account.management_ended            |
| `AccountNifAdded`                   | account.nif_added                   |
| `AccountNifTransferred`             | account.nif_transferred             |
| `AccountNifArchived`                | account.nif_archived                |
| `AccountImportCompleted`            | account.import_completed            |