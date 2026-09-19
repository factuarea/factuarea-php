# PublicApiV1WarehousesListStatus

Warehouse status: `active` (in service) or `archived` (withdrawn from the operation, keeping its row, its history and every document that points at it). Archiving frees the code for reuse, so an archived warehouse and a live one may share the same code. Exact match on `status`.


## Values

| Name       | Value      |
| ---------- | ---------- |
| `Active`   | active     |
| `Archived` | archived   |