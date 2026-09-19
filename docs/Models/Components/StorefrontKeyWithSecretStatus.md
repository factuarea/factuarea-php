# StorefrontKeyWithSecretStatus

Status of the credential: `active` or `revoked`. Revoking is TERMINAL —a revoked credential is never brought back— and it is published as its own axis because a credential can also stop working by expiring, which is a different fact.


## Values

| Name      | Value     |
| --------- | --------- |
| `Active`  | active    |
| `Revoked` | revoked   |