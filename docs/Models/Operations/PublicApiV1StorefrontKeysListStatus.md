# PublicApiV1StorefrontKeysListStatus

Status of the publishable key: `active` (it still authenticates a storefront) or `revoked` (killed for good, and revoking is permanent). Revoking never takes a key out of this listing — the trace of what was issued and of what was killed stays readable —, so this filter is how you keep only the ones a storefront can still use. Exact match on `status`.


## Values

| Name      | Value     |
| --------- | --------- |
| `Active`  | active    |
| `Revoked` | revoked   |