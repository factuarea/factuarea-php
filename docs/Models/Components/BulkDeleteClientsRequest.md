# BulkDeleteClientsRequest

Public REST API v1 — POST /v1/clients/bulk-delete (canónico) y
DELETE /v1/clients/bulk (deprecado). Mismo request para ambas rutas.

Body: `{ ids: string[] }`. Acepta entre 1 y 200 UUIDs. La validación
de pertenencia al tenant la realiza el Handler (filtrado por company_id);
los UUIDs ajenos se ignoran silenciosamente y aparecerán en `failed`.


## Fields

| Field              | Type               | Required           | Description        |
| ------------------ | ------------------ | ------------------ | ------------------ |
| `ids`              | array<*string*>    | :heavy_check_mark: | N/A                |